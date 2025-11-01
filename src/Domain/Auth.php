<?php

namespace App\Domain;

class Auth {
    public string $user_id;
    public ?string $jwt_token;
    public ?string $jwt_expiry;
    public ?string $twofa_code;
    public ?string $twofa_code_expiry;
    public bool $is_authenticated;
    public ?string $last_login;

    public function __construct(array $data) {
        $this->user_id = $data['user_id'];
        $this->jwt_token = $data['jwt_token'] ?? null;
        $this->jwt_expiry = $data['jwt_expiry'] ?? null;
        $this->twofa_code = $data['twofa_code'] ?? null;
        $this->twofa_code_expiry = $data['twofa_code_expiry'] ?? null;
        $this->is_authenticated = (bool)$data['is_authenticated'];
        $this->last_login = $data['last_login'] ?? null;
    }
}