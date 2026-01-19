import { useEffect } from 'react';
import { useGameStore } from '@/store/gameStore';
import { GameSession } from '@/types';

const STORAGE_KEY = 'braquage-game-storage';

export function usePersistedStore() {
  const store = useGameStore();

  // Load from localStorage on mount
  useEffect(() => {
    if (typeof window === 'undefined') return;

    try {
      const saved = localStorage.getItem(STORAGE_KEY);
      if (!saved) return;

      const parsed = JSON.parse(saved);

      // Restore sessions
      if (parsed.sessions && Array.isArray(parsed.sessions)) {
        const sessions = parsed.sessions.map((s: any) => ({
          ...s,
          createdAt: new Date(s.createdAt),
          state: {
            ...s.state,
            visitedScenes: new Set(s.state.visitedScenes || []),
            completedScenes: new Set(s.state.completedScenes || []),
          },
        }));
        useGameStore.setState({ sessions });
      }

      // Restore current session
      if (parsed.currentSession) {
        const session: GameSession = {
          ...parsed.currentSession,
          createdAt: new Date(parsed.currentSession.createdAt),
          state: {
            ...parsed.currentSession.state,
            visitedScenes: new Set(parsed.currentSession.state.visitedScenes || []),
            completedScenes: new Set(parsed.currentSession.state.completedScenes || []),
          },
        };
        useGameStore.setState({ currentSession: session });
      }

      // Restore scenes
      if (parsed.scenes) {
        const scenesMap = new Map();
        Object.entries(parsed.scenes).forEach(([key, value]) => {
          scenesMap.set(key, value);
        });
        useGameStore.setState({ scenes: scenesMap });
      }
    } catch (error) {
      console.error('Failed to load persisted state:', error);
    }
  }, []);

  // Save to localStorage whenever state changes
  useEffect(() => {
    if (typeof window === 'undefined') return;

    const unsubscribe = useGameStore.subscribe((state) => {
      const stateToSave = {
        currentSession: state.currentSession
          ? {
              ...state.currentSession,
              state: {
                ...state.currentSession.state,
                visitedScenes: Array.from(state.currentSession.state.visitedScenes),
                completedScenes: Array.from(state.currentSession.state.completedScenes),
              },
            }
          : null,
        sessions: state.sessions.map((s) => ({
          ...s,
          state: {
            ...s.state,
            visitedScenes: Array.from(s.state.visitedScenes),
            completedScenes: Array.from(s.state.completedScenes),
          },
        })),
        scenes: Object.fromEntries(state.scenes),
      };

      try {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(stateToSave));
      } catch (error) {
        console.error('Failed to save state:', error);
      }
    });

    return unsubscribe;
  }, []);
}

