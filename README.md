## Configuration obligatoire

Avant de lancer le projet, crée un fichier `.env` à la racine et renseigne :

- `GITHUB_CLIENT_ID` et `GITHUB_CLIENT_SECRET` : récupérés sur [GitHub Developer Settings](https://github.com/settings/developers) après création d’une application OAuth.
- `GITHUB_REDIRECT_URI` : l’URL de callback (ex : `http://localhost:8080/callback`).
- `MAIL_FROM` : ton adresse email d’envoi (ex : Gmail).
- `MAIL_PASSWORD` : le mot de passe d’application SMTP (pour Gmail, génère-le dans les paramètres de sécurité Google).
- `DEFAULT_USER_EMAIL` : email par défaut si l’utilisateur n’en a pas.
- `JWT_SECRET`, `JWT_ISSUER`, `JWT_TTL` : pour la génération des tokens JWT.

2. Configuration GitHub
Crée une application OAuth sur github.com/settings/developers
Mets l’URL de callback : http://localhost:8080/callback
Récupère le client_id et client_secret pour .env

**Exemple de .env :**
```
GITHUB_CLIENT_ID=ton_client_id_github
GITHUB_CLIENT_SECRET=ton_client_secret_github
GITHUB_REDIRECT_URI=http://localhost:8080/callback

MAIL_FROM=tonemail@gmail.com
MAIL_PASSWORD=mot_de_passe_application_gmail
DEFAULT_USER_EMAIL=tonemail@gmail.com

JWT_SECRET=une_cle_secrete_pour_jwt
JWT_ISSUER=your-app
JWT_TTL=3600
```

Assure-toi que toutes ces variables sont bien renseignées avant de lancer le serveur.


. Bibliothèques à installer
composer install


3. Configuration PHP/cURL (Windows)
Télécharge cacert.pem sur https://curl.se/ca/cacert.pem
Place-le dans ton dossier PHP (ex : extras/ssl)
Ajoute dans php.ini (sans point-virgule) :

curl.cainfo = "C:\chemin\vers\cacert.pem"



🔒 Fonctionnement
Login : L’utilisateur se connecte via GitHub OAuth.
Callback : Le serveur échange le code contre un token, récupère le profil GitHub.
2FA : L’utilisateur choisit le facteur (email recommandé). Un code est envoyé.
Vérification : L’utilisateur saisit le code reçu.
JWT : Si 2FA OK, un JWT est généré et stocké en session.
Accès : L’utilisateur peut accéder aux routes protégées.

