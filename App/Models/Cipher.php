<?php

namespace Monster\App\Models;

class Cipher
{
    private $key;
    private $cipher;

    public function __construct($key, $cipher)
    {
        $this->key = $key;
        $this->cipher = $cipher;
    }

    public function encrypt($data)
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->cipher));
        $encrypted = openssl_encrypt($data, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($iv . $encrypted);
    }

    public function decrypt($encryptedData)
    {
        $encryptedData = base64_decode($encryptedData);
        $ivLength = openssl_cipher_iv_length($this->cipher);
        $iv = substr($encryptedData, 0, $ivLength);
        $encrypted = substr($encryptedData, $ivLength);
        return openssl_decrypt($encrypted, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv);
    }
}


/*
$key = "your_secret_key";
$cipher = "AES-256-CBC";
$encryption = new Cipher($key, $cipher);

$plainText = "Hello, World!";
$encryptedText = $encryption->encrypt($plainText);
$decryptedText = $encryption->decrypt($encryptedText);

echo "Plain Text: " . $plainText . "<br />";
echo "Encrypted Text: " . $encryptedText . "<br />";
echo "Decrypted Text: " . $decryptedText . "<br />";
*/