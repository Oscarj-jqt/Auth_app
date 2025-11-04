GITHUB_CLIENT_ID=ton_client_id_github
GITHUB_CLIENT_SECRET=ton_client_secret_github
GITHUB_REDIRECT_URI=http://localhost:8080/callback

JWT_SECRET=une_cle_secrete_pour_jwt
JWT_ISSUER=your-app
JWT_TTL=3600

MAIL_PASSWORD=ton_mot_de_passe_smtp
DEFAULT_USER_EMAIL=tonemail@.com




. Bibliothèques à installer
composer install

3. Configuration GitHub
Crée une application OAuth sur github.com/settings/developers
Mets l’URL de callback : http://localhost:8080/callback
Récupère le client_id et client_secret pour .env


4. Configuration PHP/cURL (Windows)
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

Donc caprès autorsation oauth github couche de sécurité en plus avec 2fa au choix email 
-> au final une personne vérifiée par github + email la sécurité est optimale (cas d'usage ici exercice)