# Guide de Déploiement Docker

## Construction et Lancement

### Développement Local

```bash
# Construire et lancer
docker-compose up -d --build

# Voir les logs
docker-compose logs -f

# Arrêter
docker-compose down
```

### Production

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

## Persistance des Données

Les données de parties sont actuellement stockées dans le **localStorage du navigateur** de chaque utilisateur. Cela signifie que :

- ✅ Les données persistent même si le conteneur Docker est recréé
- ✅ Chaque utilisateur a ses propres données dans son navigateur
- ✅ Pas besoin de volume Docker pour les données utilisateur

### Si vous souhaitez une persistance serveur

Si vous voulez sauvegarder les données dans un volume Docker (pour partage entre utilisateurs ou backup), vous devrez :

1. Créer une API pour sauvegarder/charger les données
2. Modifier le système de stockage pour utiliser cette API au lieu de localStorage
3. Monter un volume Docker pour stocker les fichiers de données

Exemple avec volume (pour future implémentation) :
```bash
docker run -d \
  -p 3001:3000 \
  -v /chemin/vers/game-sessions:/app/data/game-sessions \
  --name braquage-app \
  braquage-game-manager
```

## Architecture

- **Image Docker** : Contient l'application Next.js compilée et toutes les scènes intégrées
- **Données** : Les scènes sont intégrées dans l'image (dans `/data/scenes.ts`)
- **Cache utilisateur** : Stocké dans le localStorage du navigateur (côté client)

## Ports

L'application écoute sur le port **3001** en externe (mappé depuis le port 3000 interne du conteneur).

Pour changer le port externe :
```bash
docker run -d \
  -p 8080:3000 \
  --name braquage-app \
  braquage-game-manager
```

## Healthcheck

Le conteneur inclut un healthcheck qui vérifie que l'application répond correctement.

## Logs

```bash
# Logs en temps réel
docker logs -f braquage-app

# Dernières 100 lignes
docker logs --tail 100 braquage-app
```

