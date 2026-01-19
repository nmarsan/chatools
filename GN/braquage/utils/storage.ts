// Storage utility that works both in browser (localStorage) and can be extended for file-based storage
// For Docker deployment, we'll use localStorage which persists in the browser
// The actual persistence is handled by the browser, but the data structure is designed to be portable

export interface StoredGameData {
  currentSession: any | null;
  sessions: any[];
  scenes: Record<string, any>;
}

const STORAGE_KEY = 'braquage-game-storage';

export const storage = {
  get: (): StoredGameData | null => {
    if (typeof window === 'undefined') return null;
    
    try {
      const item = localStorage.getItem(STORAGE_KEY);
      if (!item) return null;
      return JSON.parse(item);
    } catch (error) {
      console.error('Failed to read from storage:', error);
      return null;
    }
  },

  set: (data: StoredGameData): void => {
    if (typeof window === 'undefined') return;
    
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
    } catch (error) {
      console.error('Failed to write to storage:', error);
    }
  },

  clear: (): void => {
    if (typeof window === 'undefined') return;
    localStorage.removeItem(STORAGE_KEY);
  },
};

