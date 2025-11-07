# Auth_app – Authentification OAuth GitHub + 2FA (email ou QR code)

Cette application PHP permet à un utilisateur de se connecter via GitHub OAuth, puis de valider son identité avec une double authentification : par email ou via QR code compatible Google Authenticator (TOTP).  
Après validation, il accède à une route protégée affichant ses informations.

---

## Parcours utilisateur OAuth & 2FA

1. L’utilisateur se connecte via GitHub OAuth et autorise l’application
2. Il choisit sa méthode de double authentification : email ou QR code (TOTP)
3. S’il choisit email, il reçoit un code à saisir ; s’il choisit QR code, il scanne avec Google Authenticator et saisit le code généré
4. Une fois la 2FA validée, il accède à la route protégée qui affiche :
   - Son nom d’utilisateur GitHub et son ID
   - Son email (si 2FA email)
   - Le type d’authentification 2FA utilisé
   - Un message spécifique si la connexion a été validée par QR code

---

## Prérequis

- PHP >= 8.0
- Composer
- Un compte GitHub
- Un compte Gmail (pour l’envoi d’emails)

---

## 1. Installation des dépendances

```bash
composer install
```

---

## 2. Configuration de l’application

### Où trouver les identifiants GitHub OAuth ?

Pour obtenir les valeurs `GITHUB_CLIENT_ID`, `GITHUB_CLIENT_SECRET` et définir `GITHUB_REDIRECT_URI` :

1. Va sur [GitHub Developer Settings](https://github.com/settings/developers)
2. Clique sur "New OAuth App"
3. Renseigne :
   - **Application name** : le nom de ton projet
   - **Homepage URL** : `https://localhost/public/index.php`
   - **Authorization callback URL** : `http://localhost:8080/callback`
4. Valide la création, puis copie :
   - Le **Client ID** dans `GITHUB_CLIENT_ID`
   - Le **Client Secret** dans `GITHUB_CLIENT_SECRET`
   - L’URL de callback dans `GITHUB_REDIRECT_URI` (doit être identique à celle renseignée sur GitHub)

### 2.1. Création du fichier `.env`

Crée un fichier `.env` à la racine du projet et renseigne :

```
GITHUB_CLIENT_ID=ton_client_id_github
GITHUB_CLIENT_SECRET=ton_client_secret_github
GITHUB_REDIRECT_URI=http://localhost:8080/callback

MAIL_FROM=tonemail@gmail.com
MAIL_PASSWORD="mot_de_passe_application_gmail"
DEFAULT_USER_EMAIL=tonemail@gmail.com

JWT_SECRET=une_cle_secrete_pour_jwt
JWT_ISSUER=your-app
JWT_TTL=3600
```

---

### 2.2. Configuration GitHub OAuth

1. Va sur [GitHub Developer Settings](https://github.com/settings/developers)
2. Crée une application OAuth
3. Mets l’URL de callback : `http://localhost:8080/callback`
4. Récupère le client_id et client_secret et renseigne-les dans `.env`

---

### 2.3. Configuration Gmail pour l’envoi d’emails

Pour utiliser l’envoi d’emails avec Gmail :

1. **Active la double authentification (2FA) sur ton compte Google**  
   Va dans les paramètres de sécurité de ton compte Google et active la validation en deux étapes.

2. **Génère un mot de passe d’application**  
   - Va sur : [https://myaccount.google.com/apppasswords](https://myaccount.google.com/apppasswords)
   - Si tu n’as pas activé la 2FA, cette page te demandera de le faire.
   - Crée un mot de passe d’application (nomme-le par exemple "OAuth App").
   - Copie ce mot de passe et colle-le dans la variable `MAIL_PASSWORD` de ton fichier `.env`.

**Attention :**  
Si tu désactives puis réactives la 2FA, tu dois générer un nouveau mot de passe d’application : les anciens sont supprimés automatiquement.

---

### 2.4. Configuration PHP/cURL (Windows)

- Télécharge [cacert.pem](https://curl.se/ca/cacert.pem)
- Place-le dans ton dossier PHP (ex : extras/ssl)
- Ajoute dans `php.ini` :
  ```
  curl.cainfo = "C:\chemin\vers\cacert.pem"
  ```
- Active l’extension GD pour la génération de QR code :
  ```
  extension=gd
  ```

---

## 3. Double authentification TOTP (QR code)

- Installe une application compatible TOTP sur ton téléphone :
  - [Google Authenticator](https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2)
  - Authy, Microsoft Authenticator, etc.
- Lors du choix "Google Authenticator (TOTP)", scanne le QR code affiché avec l’application
- Saisis le code généré pour valider ta connexion

---