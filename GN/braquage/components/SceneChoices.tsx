'use client';

import { Choice } from '@/types';
import { useGameStore } from '@/store/gameStore';
import { ArrowRight } from 'lucide-react';

interface SceneChoicesProps {
  choices: Choice[];
  sceneId: string;
}

export default function SceneChoices({ choices, sceneId }: SceneChoicesProps) {
  const { goToScene, completeScene, currentSession } = useGameStore();

  if (!choices || choices.length === 0) {
    return (
      <div className="mt-4 p-4 bg-gray-100 rounded-lg text-center text-gray-500">
        Aucun choix disponible pour cette scène
      </div>
    );
  }

  const handleChoiceClick = (choice: Choice) => {
    completeScene(sceneId);
    goToScene(choice.targetSceneId, choice.id);
  };

  const hasVisited = (targetSceneId: string) => {
    return currentSession?.state.visitedScenes.has(targetSceneId) || false;
  };

  return (
    <div className="mt-6 space-y-3">
      <h3 className="text-lg font-semibold text-gray-800 mb-3">Choix possibles :</h3>
      {choices.map((choice) => {
        const visited = hasVisited(choice.targetSceneId);
        return (
          <button
            key={choice.id}
            onClick={() => handleChoiceClick(choice)}
            className={`w-full p-4 rounded-lg border-2 transition-all text-left ${
              visited
                ? 'bg-green-50 border-green-300 hover:bg-green-100'
                : 'bg-white border-gray-300 hover:border-blue-500 hover:bg-blue-50'
            }`}
          >
            <div className="flex items-center justify-between">
              <span className="text-gray-800">{choice.description}</span>
              <div className="flex items-center gap-2">
                {visited && (
                  <span className="text-xs text-green-600 font-semibold">(Visité)</span>
                )}
                <ArrowRight className="w-5 h-5 text-gray-400" />
              </div>
            </div>
            <div className="text-xs text-gray-500 mt-1">
              → Scène {choice.targetSceneId}
            </div>
          </button>
        );
      })}
    </div>
  );
}

