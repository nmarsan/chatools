'use client';

import { useEffect } from 'react';
import { useGameStore } from '@/store/gameStore';
import SceneViewer from '@/components/SceneViewer';
import SceneChoices from '@/components/SceneChoices';
import SceneTree from '@/components/SceneTree';
import SessionManager from '@/components/SessionManager';
import { usePersistedStore } from '@/hooks/usePersistedStore';
import InfoBanner from '@/components/InfoBanner';
import { scenesData } from '../lib/scenes';
import { ArrowLeft } from 'lucide-react';

export default function Home() {
  // Initialize persistence
  usePersistedStore();
  
  const { currentSession, getCurrentScene, goBack, scenes, addScenes } = useGameStore();
  const currentScene = getCurrentScene();
  
  // Load scenes data on mount
  useEffect(() => {
    if (scenes.size === 0) {
      addScenes(scenesData);
    }
  }, [scenes.size, addScenes]);

  const canGoBack =
    currentSession && currentSession.state.sceneHistory.length > 1;

  return (
    <div className="min-h-screen bg-gray-100">
      <div className="container mx-auto p-4">
        <div className="mb-4 flex items-center justify-between">
          <h1 className="text-3xl font-bold text-gray-800">Braquage - Gestionnaire de Jeu</h1>
          {canGoBack && (
            <button
              onClick={goBack}
              className="flex items-center gap-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors"
            >
              <ArrowLeft className="w-4 h-4" />
              Retour en arrière
            </button>
          )}
        </div>

        <InfoBanner />

        <div className="grid grid-cols-1 lg:grid-cols-4 gap-4">
          {/* Left Column - Main Content */}
          <div className="lg:col-span-3 space-y-4">
            {/* Session Manager */}
            <SessionManager />

            {/* Current Scene */}
            {currentSession && currentScene ? (
              <div className="bg-white rounded-lg shadow-md p-6">
                <SceneViewer scene={currentScene} />
                <SceneChoices choices={currentScene.choices || []} sceneId={currentScene.id} />
              </div>
            ) : currentSession ? (
              <div className="bg-white rounded-lg shadow-md p-6 text-center text-gray-500">
                <p>Scène introuvable. Veuillez uploader les PDFs.</p>
              </div>
            ) : (
              <div className="bg-white rounded-lg shadow-md p-6 text-center text-gray-500">
                <p>Créez une nouvelle partie pour commencer.</p>
              </div>
            )}
          </div>

          {/* Right Column - Scene Tree */}
          <div className="lg:col-span-1">
            <div className="bg-white rounded-lg shadow-md p-4 sticky top-4">
              <SceneTree />
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

