<?php

namespace Monster\App\Models;

class Cipher
{
    private $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function encrypt($message)
    {
        // Custom encryption algorithm
        $encrypted = str_rot13($message); // Example: using ROT13 substitution

        // Additional encryption steps using the key
        $encrypted = $this->xorEncrypt($encrypted);

        return $encrypted;
    }

    public function decrypt($encryptedMessage)
    {
        // Reverse the additional encryption steps using the key
        $decrypted = $this->xorDecrypt($encryptedMessage);

        // Custom decryption algorithm
        $decrypted = str_rot13($decrypted); // Example: reversing ROT13 substitution

        return $decrypted;
    }

    private function xorEncrypt($message)
    {
        $key = $this->key;
        $keyLength = strlen($key);
        $messageLength = strlen($message);
        $encrypted = '';

        for ($i = 0; $i < $messageLength; $i++) {
            $encrypted .= $message[$i] ^ $key[$i % $keyLength];
        }

        return base64_encode($encrypted);
    }

    private function xorDecrypt($encryptedMessage)
    {
        $key = $this->key;
        $keyLength = strlen($key);
        $encryptedMessage = base64_decode($encryptedMessage);
        $messageLength = strlen($encryptedMessage);
        $decrypted = '';

        for ($i = 0; $i < $messageLength; $i++) {
            $decrypted .= $encryptedMessage[$i] ^ $key[$i % $keyLength];
        }

        return $decrypted;
    }
}


// Usage example:
/*
$key = "your_secret_key";
$cipher = new Cipher($key);
$message = "Hello, World!";
$encryptedMessage = $cipher->encrypt($message);
$decryptedMessage = $cipher->decrypt($encryptedMessage);

echo "Original message: " . $message . "\n";
echo "Encrypted message: " . $encryptedMessage . "\n";
echo "Decrypted message: " . $decryptedMessage . "\n";
*/