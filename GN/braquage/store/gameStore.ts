import { create } from 'zustand';
import { GameSession, GameState, Scene } from '@/types';

interface GameStore {
  // Current session
  currentSession: GameSession | null;
  sessions: GameSession[];
  
  // Scenes data
  scenes: Map<string, Scene>;
  
  // Actions
  createNewSession: (name: string) => void;
  loadSession: (sessionId: string) => void;
  deleteSession: (sessionId: string) => void;
  
  // Scene navigation
  goToScene: (sceneId: string, choiceId?: string) => void;
  goBack: () => void;
  completeScene: (sceneId: string) => void;
  uncompleteScene: (sceneId: string) => void;
  
  // Scene data management
  addScene: (scene: Scene) => void;
  addScenes: (scenes: Scene[]) => void;
  
  // Get current scene
  getCurrentScene: () => Scene | null;
  
  // Get scene by ID
  getScene: (sceneId: string) => Scene | null;
  
  // Get all scenes for an acte
  getScenesByActe: (acte: number) => Scene[];
  
  // Check if scene is accessible
  isSceneAccessible: (sceneId: string) => boolean;
}

export const useGameStore = create<GameStore>()((set, get) => ({
      currentSession: null,
      sessions: [],
      scenes: new Map(),
      
      createNewSession: (name: string) => {
        const newSession: GameSession = {
          id: Date.now().toString(),
          name,
          createdAt: new Date(),
          state: {
            currentSceneId: '1-1', // Start at Acte 1, Scene 1
            visitedScenes: new Set(['1-1']),
            completedScenes: new Set(),
            sceneHistory: ['1-1'],
            characterChoices: {},
          },
        };
        
        set((state) => ({
          currentSession: newSession,
          sessions: [...state.sessions, newSession],
        }));
      },
      
      loadSession: (sessionId: string) => {
        const session = get().sessions.find((s) => s.id === sessionId);
        if (session) {
          set({ currentSession: session });
        }
      },
      
      deleteSession: (sessionId: string) => {
        set((state) => ({
          sessions: state.sessions.filter((s) => s.id !== sessionId),
          currentSession: state.currentSession?.id === sessionId ? null : state.currentSession,
        }));
      },
      
      goToScene: (sceneId: string, choiceId?: string) => {
        const state = get();
        if (!state.currentSession) return;
        
        const newState: GameState = {
          ...state.currentSession.state,
          currentSceneId: sceneId,
          visitedScenes: new Set([...state.currentSession.state.visitedScenes, sceneId]),
          sceneHistory: [...state.currentSession.state.sceneHistory, sceneId],
        };
        
        if (choiceId) {
          newState.characterChoices = {
            ...state.currentSession.state.characterChoices,
            [state.currentSession.state.currentSceneId]: choiceId,
          };
        }
        
        set({
          currentSession: {
            ...state.currentSession,
            state: newState,
          },
        });
      },
      
      goBack: () => {
        const state = get();
        if (!state.currentSession || state.currentSession.state.sceneHistory.length <= 1) return;
        
        const newHistory = [...state.currentSession.state.sceneHistory];
        newHistory.pop(); // Remove current scene
        const previousSceneId = newHistory[newHistory.length - 1];
        
        const newState: GameState = {
          ...state.currentSession.state,
          currentSceneId: previousSceneId,
          sceneHistory: newHistory,
        };
        
        set({
          currentSession: {
            ...state.currentSession,
            state: newState,
          },
        });
      },
      
      completeScene: (sceneId: string) => {
        const state = get();
        if (!state.currentSession) return;
        
        const newState: GameState = {
          ...state.currentSession.state,
          completedScenes: new Set([...state.currentSession.state.completedScenes, sceneId]),
        };
        
        set({
          currentSession: {
            ...state.currentSession,
            state: newState,
          },
        });
      },
      
      uncompleteScene: (sceneId: string) => {
        const state = get();
        if (!state.currentSession) return;
        
        const completed = new Set(state.currentSession.state.completedScenes);
        completed.delete(sceneId);
        
        const newState: GameState = {
          ...state.currentSession.state,
          completedScenes: completed,
        };
        
        set({
          currentSession: {
            ...state.currentSession,
            state: newState,
          },
        });
      },
      
      addScene: (scene: Scene) => {
        set((state) => {
          const newScenes = new Map(state.scenes);
          newScenes.set(scene.id, scene);
          return { scenes: newScenes };
        });
      },
      
      addScenes: (scenes: Scene[]) => {
        set((state) => {
          const newScenes = new Map(state.scenes);
          scenes.forEach((scene) => newScenes.set(scene.id, scene));
          return { scenes: newScenes };
        });
      },
      
      getCurrentScene: () => {
        const state = get();
        if (!state.currentSession) return null;
        return state.scenes.get(state.currentSession.state.currentSceneId) || null;
      },
      
      getScene: (sceneId: string) => {
        return get().scenes.get(sceneId) || null;
      },
      
      getScenesByActe: (acte: number) => {
        const scenes = Array.from(get().scenes.values());
        return scenes.filter((s) => s.acte === acte).sort((a, b) => a.scene - b.scene);
      },
      
      isSceneAccessible: (sceneId: string) => {
        const state = get();
        if (!state.currentSession) return false;
        
        // First scene is always accessible
        if (sceneId === '1-1') return true;
        
        // Check if scene has been visited
        if (state.currentSession.state.visitedScenes.has(sceneId)) return true;
        
        // Check if any completed scene has a choice leading to this scene
        const scene = state.scenes.get(sceneId);
        if (!scene) return false;
        
        // Check all completed scenes for choices leading to this scene
        for (const completedSceneId of state.currentSession.state.completedScenes) {
          const completedScene = state.scenes.get(completedSceneId);
          if (completedScene?.choices?.some((c) => c.targetSceneId === sceneId)) {
            return true;
          }
        }
        
        return false;
      },
    })
);

