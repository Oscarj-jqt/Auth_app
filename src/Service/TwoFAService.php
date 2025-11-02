<?php

namespace App\Service;

/**
 * TwoFA service :
 * - generate numeric code for email/SMS
 * - generate/verify TOTP (basic RFC-6238 implementation)
 */
class TwoFAService
{
    public function generateNumericCode(int $digits = 6): string
    {
        $min = (int) str_repeat('1', 1) . str_repeat('0', $digits - 1); // e.g. 100000
        $max = (int) str_repeat('9', $digits);
        return (string) random_int($min, $max);
    }

    public function generateTotpSecret(int $length = 16): string
    {
        // generate base32 secret
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        for ($i = 0; $i < $length; $i++) {
            $secret .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $secret;
    }

    private function base32Decode(string $b32): string
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $b32 = strtoupper($b32);
        $l = strlen($b32);
        $bits = 0;
        $value = 0;
        $output = '';
        for ($i = 0; $i < $l; $i++) {
            $pos = strpos($alphabet, $b32[$i]);
            if ($pos === false) continue;
            $value = ($value << 5) + $pos;
            $bits += 5;
            if ($bits >= 8) {
                $bits -= 8;
                $output .= chr(($value & (255 << $bits)) >> $bits);
            }
        }
        return $output;
    }

    private function hotp(string $secret, int $counter, int $digits = 6): string
    {
        $key = $this->base32Decode($secret);
        $counterBytes = pack('N*', 0) . pack('N*', $counter); // 64-bit
        $hash = hash_hmac('sha1', $counterBytes, $key, true);
        $offset = ord($hash[19]) & 0x0F;
        $binary = (ord($hash[$offset]) & 0x7f) << 24 |
                  (ord($hash[$offset + 1]) & 0xff) << 16 |
                  (ord($hash[$offset + 2]) & 0xff) << 8 |
                  (ord($hash[$offset + 3]) & 0xff);
        $otp = $binary % pow(10, $digits);
        return str_pad((string)$otp, $digits, '0', STR_PAD_LEFT);
    }

    public function getTotpCode(string $secret, int $digits = 6, int $period = 30, int $time = null): string
    {
        $time = $time ?? time();
        $counter = (int)floor($time / $period);
        return $this->hotp($secret, $counter, $digits);
    }

    public function verifyTotp(string $secret, string $code, int $window = 1, int $period = 30): bool
    {
        $time = time();
        for ($i = -$window; $i <= $window; $i++) {
            $t = $time + ($i * $period);
            if (hash_equals($this->getTotpCode($secret, 6, $period, $t), $code)) {
                return true;
            }
        }
        return false;
    }

    // Placeholder functions for sending — to implement with PHPMailer/Twilio
    public function sendEmailCode(string $to, string $code): bool
    {
        // TODO: integrate PHPMailer
        // mail($to, 'Your 2FA code', "Code: $code");
        return true;
    }

    public function sendSmsCode(string $phone, string $code): bool
    {
        // TODO: integrate Twilio
        return true;
    }
}