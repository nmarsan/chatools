'use client';

import { useState } from 'react';
import { useGameStore } from '@/store/gameStore';
import { Plus, Trash2, Play } from 'lucide-react';

export default function SessionManager() {
  const { sessions, currentSession, createNewSession, loadSession, deleteSession } =
    useGameStore();
  const [showNewSessionForm, setShowNewSessionForm] = useState(false);
  const [newSessionName, setNewSessionName] = useState('');

  const handleCreateSession = () => {
    if (newSessionName.trim()) {
      createNewSession(newSessionName.trim());
      setNewSessionName('');
      setShowNewSessionForm(false);
    }
  };

  return (
    <div className="bg-white rounded-lg shadow-md p-4">
      <div className="flex items-center justify-between mb-4">
        <h2 className="text-xl font-bold text-gray-800">Parties</h2>
        <button
          onClick={() => setShowNewSessionForm(!showNewSessionForm)}
          className="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
        >
          <Plus className="w-4 h-4" />
          Nouvelle partie
        </button>
      </div>

      {showNewSessionForm && (
        <div className="mb-4 p-4 bg-gray-50 rounded-lg">
          <input
            type="text"
            value={newSessionName}
            onChange={(e) => setNewSessionName(e.target.value)}
            placeholder="Nom de la partie"
            className="w-full px-3 py-2 border border-gray-300 rounded-lg mb-2"
            onKeyPress={(e) => e.key === 'Enter' && handleCreateSession()}
          />
          <div className="flex gap-2">
            <button
              onClick={handleCreateSession}
              className="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
            >
              Créer
            </button>
            <button
              onClick={() => {
                setShowNewSessionForm(false);
                setNewSessionName('');
              }}
              className="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400"
            >
              Annuler
            </button>
          </div>
        </div>
      )}

      <div className="space-y-2">
        {sessions.length === 0 ? (
          <p className="text-gray-500 text-center py-4">Aucune partie créée</p>
        ) : (
          sessions.map((session) => (
            <div
              key={session.id}
              className={`p-3 rounded-lg border-2 flex items-center justify-between ${
                currentSession?.id === session.id
                  ? 'border-blue-500 bg-blue-50'
                  : 'border-gray-200 bg-white'
              }`}
            >
              <div className="flex-1">
                <div className="font-semibold text-gray-800">{session.name}</div>
                <div className="text-xs text-gray-500">
                  Créée le {new Date(session.createdAt).toLocaleDateString('fr-FR')}
                </div>
                <div className="text-xs text-gray-500">
                  Scène actuelle: {session.state.currentSceneId}
                </div>
              </div>
              <div className="flex gap-2">
                {currentSession?.id !== session.id && (
                  <button
                    onClick={() => loadSession(session.id)}
                    className="p-2 text-blue-600 hover:bg-blue-100 rounded"
                    title="Charger cette partie"
                  >
                    <Play className="w-4 h-4" />
                  </button>
                )}
                <button
                  onClick={() => {
                    if (confirm('Êtes-vous sûr de vouloir supprimer cette partie ?')) {
                      deleteSession(session.id);
                    }
                  }}
                  className="p-2 text-red-600 hover:bg-red-100 rounded"
                  title="Supprimer cette partie"
                >
                  <Trash2 className="w-4 h-4" />
                </button>
              </div>
            </div>
          ))
        )}
      </div>
    </div>
  );
}

