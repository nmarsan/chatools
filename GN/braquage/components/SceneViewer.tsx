'use client';

import { useState } from 'react';
import { Scene, SceneContent } from '@/types';
import { ChevronDown, ChevronUp } from 'lucide-react';

interface SceneViewerProps {
  scene: Scene;
}

export default function SceneViewer({ scene }: SceneViewerProps) {
  const [expandedCharacters, setExpandedCharacters] = useState<Set<string>>(new Set());
  const [selectedCharacter, setSelectedCharacter] = useState<string | null>(null);

  const toggleCharacter = (character: string) => {
    const newExpanded = new Set(expandedCharacters);
    if (newExpanded.has(character)) {
      newExpanded.delete(character);
    } else {
      newExpanded.add(character);
    }
    setExpandedCharacters(newExpanded);
  };

  const characterNames = ['Alex', 'Charlie', 'Camille', 'Andréa', 'Oncle de Charlie', 'Corps'];

  return (
    <div className="flex flex-col h-full">
      {/* Scene Header */}
      <div className="bg-gray-800 text-white p-4 mb-4 rounded-lg">
        <h1 className="text-2xl font-bold">
          Acte {scene.acte} - Scène {scene.scene}
        </h1>
        <p className="text-sm text-gray-300 mt-1">Scène ID: {scene.id}</p>
      </div>

      {/* Character Tabs */}
      <div className="flex gap-2 mb-4 flex-wrap">
        {characterNames.map((charName) => {
          const content = scene.contents.find((c) => c.character === charName);
          if (!content) return null;

          const isSelected = selectedCharacter === charName;
          const isExpanded = expandedCharacters.has(charName);

          return (
            <button
              key={charName}
              onClick={() => {
                setSelectedCharacter(isSelected ? null : charName);
                toggleCharacter(charName);
              }}
              className={`px-4 py-2 rounded-lg transition-colors ${
                isSelected
                  ? 'bg-blue-600 text-white'
                  : 'bg-gray-200 hover:bg-gray-300 text-gray-800'
              }`}
            >
              {charName}
              {isExpanded ? (
                <ChevronUp className="inline ml-2 w-4 h-4" />
              ) : (
                <ChevronDown className="inline ml-2 w-4 h-4" />
              )}
            </button>
          );
        })}
      </div>

      {/* Character Content */}
      <div className="flex-1 overflow-y-auto space-y-4">
        {selectedCharacter ? (
          <CharacterContent
            content={scene.contents.find((c) => c.character === selectedCharacter)!}
          />
        ) : (
          expandedCharacters.size > 0 ? (
            expandedCharacters.size === 1 ? (
              <CharacterContent
                content={scene.contents.find((c) => expandedCharacters.has(c.character))!}
              />
            ) : (
              scene.contents
                .filter((c) => expandedCharacters.has(c.character))
                .map((content) => (
                  <CharacterContent key={content.character} content={content} />
                ))
            )
          ) : (
            <div className="text-center text-gray-500 py-8">
              Cliquez sur un personnage pour voir son texte
            </div>
          )
        )}
      </div>
    </div>
  );
}

function CharacterContent({ content }: { content: SceneContent }) {
  return (
    <div className="bg-white rounded-lg p-6 shadow-md border border-gray-200">
      <h2 className="text-xl font-bold mb-4 text-gray-800">{content.character}</h2>
      {content.title && (
        <h3 className="text-lg font-semibold mb-3 text-gray-700">{content.title}</h3>
      )}
      <div className="prose max-w-none">
        <div className="mb-4">
          <h4 className="font-semibold text-gray-700 mb-2">Introduction</h4>
          <p className="text-gray-600 whitespace-pre-line">{content.introduction}</p>
        </div>
        {content.information && (
          <div className="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <h4 className="font-semibold text-blue-800 mb-2">Information</h4>
            <p className="text-blue-700 whitespace-pre-line">{content.information}</p>
          </div>
        )}
      </div>
    </div>
  );
}

