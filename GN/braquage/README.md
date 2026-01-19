# Braquage - Gestionnaire de Jeu Théâtral

Application web pour gérer le jeu de rôle théâtral "Braquage", organisé comme un livre dont vous êtes le héros avec plusieurs personnages.

## Fonctionnalités

- 🎭 **Affichage des Scènes** : Visualisez la scène actuelle avec les textes de chaque personnage
- 🌳 **Arbre de Navigation** : Visualisez l'arbre complet des scènes avec leur statut (visité, complété, verrouillé)
- 💾 **Gestion des Parties** : Créez et gérez plusieurs parties avec sauvegarde automatique
- 🔄 **Navigation** : Naviguez entre les scènes via les choix disponibles
- ⏪ **Retour en Arrière** : Revenez en arrière dans l'historique sans perdre votre progression
- ✅ **Validation de Scènes** : Validez les scènes complétées (cliquez à nouveau pour dévalider)

## Installation

### Option 1 : Docker (Recommandé)

1. Construisez et lancez le conteneur :
```bash
docker-compose up -d --build
```

2. L'application sera accessible sur [http://localhost:3001](http://localhost:3001)

3. Les données de parties sont sauvegardées dans le navigateur (localStorage) et persistent même si le conteneur est recréé

### Option 2 : Développement Local

1. Installez les dépendances :
```bash
npm install
```

2. Lancez le serveur de développement :
```bash
npm run dev
```

3. Ouvrez [http://localhost:3000](http://localhost:3000) dans votre navigateur

### Déploiement en Production

Pour déployer l'application avec Docker :

```bash
# Construire l'image
docker build -t braquage-game-manager .

# Lancer le conteneur
docker run -d \
  -p 3001:3000 \
  --name braquage-app \
  --restart unless-stopped \
  braquage-game-manager
```

**Note sur la persistance** : Les données de parties sont stockées dans le localStorage du navigateur. Chaque utilisateur a ses propres données dans son navigateur. Si vous souhaitez une persistance côté serveur, vous pouvez modifier le système de stockage pour utiliser une API et sauvegarder dans un volume Docker.

## Utilisation

### 1. Créer une Partie

1. Cliquez sur "Nouvelle partie" pour créer une partie
2. Donnez un nom à votre partie
3. Les scènes sont automatiquement chargées depuis les données intégrées

### 2. Navigation dans le Jeu

- **Scène Actuelle** : La scène en cours s'affiche au centre avec :
  - Le numéro de l'acte et de la scène
  - Les onglets pour voir le texte de chaque personnage
  - Les choix disponibles pour passer à la scène suivante

- **Arbre des Scènes** : À droite, visualisez :
  - Toutes les scènes organisées par acte
  - Le statut de chaque scène :
    - 🔵 Bleu : Scène actuelle
    - ✅ Vert : Scène complétée (cliquez pour dévalider)
    - 🟡 Jaune : Scène visitée
    - ⚪ Gris : Scène accessible
    - 🔒 Gris foncé : Scène verrouillée

- **Choix** : Cliquez sur un choix pour naviguer vers la scène suivante. La scène actuelle sera automatiquement validée.

- **Retour en Arrière** : Utilisez le bouton "Retour en arrière" pour revenir à la scène précédente sans perdre votre progression.

### 3. Gestion des Parties

- **Créer une Partie** : Cliquez sur "Nouvelle partie" et donnez-lui un nom
- **Charger une Partie** : Cliquez sur l'icône play à côté d'une partie pour la charger
- **Supprimer une Partie** : Cliquez sur l'icône poubelle pour supprimer une partie

## Structure des Données

Les scènes sont organisées par :
- **Acte** : Numéro de l'acte (1, 2, 3, ...)
- **Scène** : Numéro de la scène dans l'acte (1, 2, 3, ...)
- **ID de Scène** : Format `acte-scene` (ex: `1-1`, `1-2`, `2-1`)

Chaque scène contient :
- Les textes de chaque personnage (Introduction + Information optionnelle)
- Les choix disponibles pour naviguer vers d'autres scènes

## Sauvegarde

Les données sont sauvegardées automatiquement dans le localStorage du navigateur :
- Les parties créées
- La progression de chaque partie
- Les scènes visitées et complétées

**Note** : Les données sont stockées localement dans le navigateur de chaque utilisateur. Elles persistent même si le conteneur Docker est recréé, car elles sont stockées côté client.

## Technologies Utilisées

- **Next.js 14** : Framework React
- **TypeScript** : Typage statique
- **Tailwind CSS** : Styling
- **Zustand** : Gestion d'état
- **Lucide React** : Icônes
- **Docker** : Containerisation

## Structure des Données

Les scènes sont définies dans `/data/scenes.ts`. Pour ajouter ou modifier des scènes, éditez ce fichier directement. Les scènes sont intégrées dans l'image Docker lors du build.
