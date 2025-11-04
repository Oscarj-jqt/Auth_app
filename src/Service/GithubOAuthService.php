<?php

/**
 * Intégration GitHub OAuth Service
 */

namespace App\Service;

class GithubOAuthService
{
    private string $clientId;
    private string $redirectUri;
    private string $scope;
    private string $authorizeUrl = 'https://github.com/login/oauth/authorize';
    private string $tokenUrl = 'https://github.com/login/oauth/access_token';

    public function __construct(string $clientId, string $redirectUri, string $scope = 'user:read')
    {
        $this->clientId = $clientId;
        $this->redirectUri = $redirectUri;
        $this->scope = $scope;
    }

    /**
     * Génère l'URL d'autorisation OAuth pour Github
     */
    public function getAuthorizationUrl(string $state): string
    {
        $params = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'scope' => $this->scope,
            'state' => $state
        ];
        return $this->authorizeUrl . '?' . http_build_query($params);
    }

    /**
     * Échange le code contre un token d'accès (utilisé dans callback.php)
     */
    public function getAccessToken(string $code, string $state, string $clientSecret): ?string
    {
        $params = [
            'client_id' => $this->clientId,
            'client_secret' => $clientSecret,
            'code' => $code,
            'redirect_uri' => $this->redirectUri,
            'state' => $state
        ];

        $ch = curl_init($this->tokenUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        $curlInfo = curl_getinfo($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        if (!$data || !isset($data['access_token'])) {
            echo "<pre>Réponse GitHub : " . htmlspecialchars($response) . "</pre>";
            echo "<pre>Erreur cURL : " . htmlspecialchars($curlError) . "</pre>";
            echo "<pre>Infos cURL : " . print_r($curlInfo, true) . "</pre>";
            return null;
        }

        return $data['access_token'] ?? null;
    }
}