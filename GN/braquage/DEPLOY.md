# Instructions de Déploiement

## Structure du Projet

Le projet est maintenant dans `/GN/braquage/` (ou le chemin complet sur votre serveur).

## Commandes de Lancement

### 1. Aller dans le répertoire du projet

```bash
cd /chemin/vers/chatools/GN/braquage
```

### 2. Construire et lancer avec Docker Compose

```bash
# Construire et lancer en arrière-plan
docker-compose up -d --build

# Voir les logs
docker-compose logs -f

# Arrêter
docker-compose down
```

### 3. Ou lancer directement avec Docker

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

## Configuration Apache2

### VirtualHost pour HTTP

Créez le fichier `/etc/apache2/sites-available/braquage.conf` :

```apache
<VirtualHost *:80>
    ServerName braquage.example.com
    ServerAlias www.braquage.example.com
    
    # Logs
    ErrorLog ${APACHE_LOG_DIR}/braquage-error.log
    CustomLog ${APACHE_LOG_DIR}/braquage-access.log combined
    
    # Reverse proxy vers le conteneur Docker
    ProxyPreserveHost On
    ProxyRequests Off
    
    # Proxy vers l'application Next.js (port 3001)
    ProxyPass / http://localhost:3001/
    ProxyPassReverse / http://localhost:3001/
    
    # Headers pour Next.js
    <Proxy *>
        Order deny,allow
        Allow from all
    </Proxy>
    
    # Headers HTTP nécessaires
    RequestHeader set X-Forwarded-Proto "http"
    RequestHeader set X-Forwarded-For "%{REMOTE_ADDR}s"
    RequestHeader set X-Real-IP "%{REMOTE_ADDR}s"
    
    # Timeout pour les requêtes longues
    ProxyTimeout 300
</VirtualHost>
```

### VirtualHost pour HTTPS (Recommandé)

```apache
<VirtualHost *:443>
    ServerName braquage.example.com
    ServerAlias www.braquage.example.com
    
    # SSL Configuration
    SSLEngine on
    SSLCertificateFile /etc/ssl/certs/braquage.crt
    SSLCertificateKeyFile /etc/ssl/private/braquage.key
    # Si vous utilisez Let's Encrypt :
    # SSLCertificateFile /etc/letsencrypt/live/braquage.example.com/fullchain.pem
    # SSLCertificateKeyFile /etc/letsencrypt/live/braquage.example.com/privkey.pem
    
    # Logs
    ErrorLog ${APACHE_LOG_DIR}/braquage-ssl-error.log
    CustomLog ${APACHE_LOG_DIR}/braquage-ssl-access.log combined
    
    # Reverse proxy vers le conteneur Docker
    ProxyPreserveHost On
    ProxyRequests Off
    
    # Proxy vers l'application Next.js (port 3001)
    ProxyPass / http://localhost:3001/
    ProxyPassReverse / http://localhost:3001/
    
    # Headers pour Next.js
    <Proxy *>
        Order deny,allow
        Allow from all
    </Proxy>
    
    # Headers HTTP nécessaires
    RequestHeader set X-Forwarded-Proto "https"
    RequestHeader set X-Forwarded-For "%{REMOTE_ADDR}s"
    RequestHeader set X-Real-IP "%{REMOTE_ADDR}s"
    
    # Timeout pour les requêtes longues
    ProxyTimeout 300
    
    # Sécurité
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-Content-Type-Options "nosniff"
</VirtualHost>

# Redirection HTTP vers HTTPS
<VirtualHost *:80>
    ServerName braquage.example.com
    ServerAlias www.braquage.example.com
    
    Redirect permanent / https://braquage.example.com/
</VirtualHost>
```

### Activation des Modules Apache

```bash
sudo a2enmod proxy
sudo a2enmod proxy_http
sudo a2enmod headers
sudo a2enmod ssl  # Si vous utilisez HTTPS
sudo a2enmod rewrite  # Pour les redirections
```

### Activation du Site

```bash
# Activer le site
sudo a2ensite braquage.conf

# Recharger Apache
sudo systemctl reload apache2

# Ou redémarrer Apache
sudo systemctl restart apache2
```

## Vérifications

1. **Vérifier que le conteneur Docker tourne** :
```bash
docker ps | grep braquage
```

2. **Vérifier que le port 3001 est accessible** :
```bash
curl http://localhost:3001
```

3. **Vérifier la configuration Apache** :
```bash
sudo apache2ctl configtest
```

4. **Tester depuis l'extérieur** :
```bash
curl http://votre-domaine.com
```

## Notes Importantes

- **Port** : L'application est exposée sur le port **3001** (mappé depuis le port 3000 interne)
- **ProxyPass** : Utilisez `http://localhost:3001/` dans votre configuration Apache
- **Domaine** : Remplacez `braquage.example.com` par votre domaine réel
- **SSL** : Pour la production, utilisez HTTPS avec Let's Encrypt (certbot)

## Dépannage

### Le conteneur ne démarre pas
```bash
docker logs braquage-game-manager
```

### Apache ne peut pas se connecter au conteneur
- Vérifiez que le conteneur écoute bien sur le port 3001 : `docker ps`
- Vérifiez les logs Apache : `sudo tail -f /var/log/apache2/braquage-error.log`

### Erreur 502 Bad Gateway
- Vérifiez que le conteneur Docker est en cours d'exécution
- Vérifiez que le port 3001 est bien exposé
- Vérifiez les permissions Apache

