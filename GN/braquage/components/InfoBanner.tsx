'use client';

import { Info } from 'lucide-react';

export default function InfoBanner() {
  return (
    <div className="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
      <div className="flex items-start gap-3">
        <Info className="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" />
        <div className="text-sm text-blue-800">
          <p className="font-semibold mb-1">Comment utiliser l'application :</p>
          <ul className="list-disc list-inside space-y-1 ml-2">
            <li>Créez une nouvelle partie pour commencer</li>
            <li>Cliquez sur un personnage pour voir son texte dans la scène actuelle</li>
            <li>Utilisez les choix pour naviguer vers la scène suivante</li>
            <li>Visualisez votre progression dans l'arbre des scènes à droite</li>
            <li>Cliquez sur une scène complétée (verte) pour la dévalider</li>
          </ul>
        </div>
      </div>
    </div>
  );
}

