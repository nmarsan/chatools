'use client';

import { useGameStore } from '@/store/gameStore';
import { Scene } from '@/types';
import { CheckCircle2, Circle, XCircle } from 'lucide-react';

export default function SceneTree() {
  const {
    scenes,
    currentSession,
    getScenesByActe,
    isSceneAccessible,
    goToScene,
    uncompleteScene,
  } = useGameStore();

  if (!currentSession) {
    return (
      <div className="text-center text-gray-500 p-4">
        Aucune partie en cours
      </div>
    );
  }

  // Get all actes
  const actes = Array.from(new Set(Array.from(scenes.values()).map((s) => s.acte))).sort();

  const getSceneStatus = (sceneId: string) => {
    if (currentSession.state.currentSceneId === sceneId) {
      return 'current';
    }
    if (currentSession.state.completedScenes.has(sceneId)) {
      return 'completed';
    }
    if (currentSession.state.visitedScenes.has(sceneId)) {
      return 'visited';
    }
    if (isSceneAccessible(sceneId)) {
      return 'accessible';
    }
    return 'locked';
  };

  const handleSceneClick = (scene: Scene) => {
    const status = getSceneStatus(scene.id);
    if (status === 'locked') return;

    if (status === 'completed') {
      // Uncomplete the scene
      uncompleteScene(scene.id);
    } else if (status !== 'current') {
      // Navigate to scene
      goToScene(scene.id);
    }
  };

  return (
    <div className="h-full overflow-y-auto">
      <h2 className="text-xl font-bold mb-4 text-gray-800 sticky top-0 bg-white pb-2">
        Arbre des Scènes
      </h2>
      <div className="space-y-6">
        {actes.map((acte) => {
          const acteScenes = getScenesByActe(acte);
          return (
            <div key={acte} className="border-l-2 border-gray-300 pl-4">
              <h3 className="font-semibold text-lg text-gray-700 mb-2">Acte {acte}</h3>
              <div className="space-y-2">
                {acteScenes.map((scene) => {
                  const status = getSceneStatus(scene.id);
                  const isCurrent = status === 'current';
                  const isCompleted = status === 'completed';
                  const isVisited = status === 'visited';
                  const isAccessible = status === 'accessible';
                  const isLocked = status === 'locked';

                  return (
                    <div
                      key={scene.id}
                      className={`flex items-center gap-2 p-2 rounded cursor-pointer transition-colors ${
                        isCurrent
                          ? 'bg-blue-100 border-2 border-blue-500'
                          : isCompleted
                          ? 'bg-green-50 hover:bg-green-100 border border-green-300'
                          : isVisited
                          ? 'bg-yellow-50 hover:bg-yellow-100 border border-yellow-300'
                          : isAccessible
                          ? 'bg-gray-50 hover:bg-gray-100 border border-gray-300'
                          : 'bg-gray-100 opacity-50 cursor-not-allowed border border-gray-200'
                      }`}
                      onClick={() => handleSceneClick(scene)}
                      title={
                        isLocked
                          ? 'Scène verrouillée'
                          : isCompleted
                          ? 'Cliquez pour dévalider'
                          : 'Cliquez pour naviguer'
                      }
                    >
                      {isCompleted ? (
                        <CheckCircle2 className="w-5 h-5 text-green-600 flex-shrink-0" />
                      ) : isVisited ? (
                        <Circle className="w-5 h-5 text-yellow-600 flex-shrink-0" />
                      ) : isAccessible ? (
                        <Circle className="w-5 h-5 text-gray-400 flex-shrink-0" />
                      ) : (
                        <XCircle className="w-5 h-5 text-gray-300 flex-shrink-0" />
                      )}
                      <span
                        className={`text-sm ${
                          isCurrent
                            ? 'font-bold text-blue-800'
                            : isCompleted
                            ? 'text-green-800'
                            : isVisited
                            ? 'text-yellow-800'
                            : isAccessible
                            ? 'text-gray-700'
                            : 'text-gray-400'
                        }`}
                      >
                        Scène {scene.scene} ({scene.id})
                      </span>
                    </div>
                  );
                })}
              </div>
            </div>
          );
        })}
      </div>
    </div>
  );
}

