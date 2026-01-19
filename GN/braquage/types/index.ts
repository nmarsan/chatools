export type Character = 'Alex' | 'Charlie' | 'Camille' | 'Andréa' | 'Oncle de Charlie' | 'Corps';

export interface SceneContent {
  character: Character;
  title?: string;
  introduction: string;
  information?: string;
}

export interface Scene {
  id: string; // Format: "acte-scene" (e.g., "1-1")
  acte: number;
  scene: number;
  contents: SceneContent[]; // One for each character
  choices?: Choice[];
}

export interface Choice {
  id: string;
  description: string;
  targetSceneId: string; // Scene to go to when this choice is selected
  conditions?: string[]; // Optional conditions for this choice
}

export interface GameState {
  currentSceneId: string;
  visitedScenes: Set<string>; // Scenes that have been visited
  completedScenes: Set<string>; // Scenes that have been completed/validated
  sceneHistory: string[]; // History of visited scenes for navigation
  characterChoices: Record<string, string>; // Scene ID -> Choice ID mapping
}

export interface GameSession {
  id: string;
  name: string;
  createdAt: Date;
  state: GameState;
}

