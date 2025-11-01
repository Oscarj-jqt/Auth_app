<?php

namespace App\Domain;

class User {
    public string $id;
    public string $username;
    public string $email;
    public ?string $phone;
    public ?string $password_hash;
    public ?string $github_id;
    public string $twofa_type; // 'email', 'sms', 'totp'
    public ?string $twofa_secret;
    public bool $is_twofa_verified;
    public string $created_at;
    public string $updated_at;

    public function __construct(array $data) {
        $this->id = $data['id'];
        $this->username = $data['username'];
        $this->email = $data['email'];
        $this->phone = $data['phone'] ?? null;
        $this->password_hash = $data['password_hash'] ?? null;
        $this->github_id = $data['github_id'] ?? null;
        $this->twofa_type = $data['twofa_type'];
        $this->twofa_secret = $data['twofa_secret'] ?? null;
        $this->is_twofa_verified = (bool)$data['is_twofa_verified'];
        $this->created_at = $data['created_at'];
        $this->updated_at = $data['updated_at'];
    }

    
}
