# Braquage - Gestionnaire de Partie PHP

Application web PHP 8.4 pour gérer les parties du jeu de rôle théâtral "Braquage".

## Prérequis

- PHP 8.4 avec les extensions suivantes :
  - php-fpm (si utilisation avec Apache)
  - json
- Apache2 (ou autre serveur web compatible)
- Permissions d'écriture sur le répertoire `data/sessions/`

## Installation

1. **Placer les fichiers** dans un répertoire accessible par Apache :
   ```bash
   /data/www/chatools/GN/braquage-php/
   ```

2. **Configurer les permissions** :
   ```bash
   chmod -R 755 /data/www/chatools/GN/braquage-php
   chmod -R 775 /data/www/chatools/GN/braquage-php/data/sessions
   ```

3. **Configurer Apache** :
   
   Copier le fichier `apache-vhost.conf` dans `/etc/apache2/sites-available/braquage.conf` :
   ```bash
   sudo cp apache-vhost.conf /etc/apache2/sites-available/braquage.conf
   ```
   
   Activer le site :
   ```bash
   sudo a2ensite braquage.conf
   sudo systemctl reload apache2
   ```

   **Note** : Ajustez le chemin `DocumentRoot` et le socket PHP-FPM dans le fichier de configuration selon votre installation.

4. **Vérifier PHP-FPM** :
   
   Assurez-vous que PHP-FPM 8.4 est installé et en cours d'exécution :
   ```bash
   sudo systemctl status php8.4-fpm
   ```

   Si nécessaire, installez PHP 8.4 :
   ```bash
   sudo apt update
   sudo apt install php8.4-fpm php8.4-cli php8.4-json
   ```

## Structure du projet

```
braquage-php/
├── index.php              # Point d'entrée principal
├── config/
│   └── config.php         # Configuration
├── src/
│   ├── GameManager.php    # Gestion du jeu
│   ├── SceneManager.php   # Gestion des scènes
│   └── SessionManager.php # Gestion des parties
├── data/
│   ├── scenes.php         # Données des scènes
│   └── sessions/          # Fichiers JSON des parties (créé automatiquement)
├── templates/
│   └── index.php          # Template principal
├── assets/
│   ├── css/
│   │   └── style.css      # Styles CSS
│   └── js/
│       └── app.js         # JavaScript
├── .htaccess              # Configuration Apache
└── README.md              # Ce fichier
```

## Utilisation

1. Accéder à l'application via votre navigateur :
   ```
   http://votre-domaine/
   ```

2. **Créer une nouvelle partie** :
   - Cliquer sur "Nouvelle Partie"
   - Entrer un nom (optionnel)
   - La partie commence à la scène 1-1

3. **Naviguer dans les scènes** :
   - Cliquer sur les onglets pour voir le texte de chaque personnage
   - Cliquer sur un choix pour aller à la scène suivante
   - Cliquer sur une scène dans l'arbre à droite pour y aller directement
   - Utiliser "Retour en arrière" pour revenir à la scène précédente

4. **Marquer les scènes comme complétées** :
   - Cliquer sur le bouton "○" à côté d'une scène visitée pour la marquer comme complétée
   - Cliquer sur "✓" pour la démarquer

## Sauvegarde des parties

Les parties sont sauvegardées dans `data/sessions/sessions.json`. Ce fichier peut être sauvegardé séparément pour conserver l'état des parties.

## Configuration Apache alternative

Si vous utilisez mod_php au lieu de PHP-FPM, modifiez le VirtualHost ainsi :

```apache
<VirtualHost *:80>
    ServerName braquage.local
    DocumentRoot /data/www/chatools/GN/braquage-php

    <Directory /data/www/chatools/GN/braquage-php>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/braquage-error.log
    CustomLog ${APACHE_LOG_DIR}/braquage-access.log combined
</VirtualHost>
```

## Dépannage

### Erreur 500
- Vérifier les logs Apache : `tail -f /var/log/apache2/braquage-error.log`
- Vérifier les permissions sur `data/sessions/`
- Vérifier que PHP 8.4 est bien installé

### Les parties ne se sauvegardent pas
- Vérifier les permissions d'écriture sur `data/sessions/`
- Vérifier que le répertoire existe

### Erreur "Module not found"
- Vérifier que tous les fichiers sont présents
- Vérifier les chemins dans `config/config.php`

## Support

Pour toute question ou problème, vérifier :
1. Les logs Apache
2. Les logs PHP-FPM : `tail -f /var/log/php8.4-fpm.log`
3. Les permissions des fichiers et répertoires

