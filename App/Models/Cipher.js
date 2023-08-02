class Cipher {
    constructor(key) {
        this.key = key;
    }

    encrypt(message) {
        // Custom encryption algorithm
        let encrypted = this.rot13(message); // Example: using ROT13 substitution

        // Additional encryption steps using the key
        encrypted = this.xorEncrypt(encrypted);

        return encrypted;
    }

    decrypt(encryptedMessage) {
        // Reverse the additional encryption steps using the key
        let decrypted = this.xorDecrypt(encryptedMessage);

        // Custom decryption algorithm
        decrypted = this.rot13(decrypted); // Example: reversing ROT13 substitution

        return decrypted;
    }

    xorEncrypt(message) {
        const key = this.key;
        const keyLength = key.length;
        const messageLength = message.length;
        let encrypted = '';

        for (let i = 0; i < messageLength; i++) {
            encrypted += String.fromCharCode(message.charCodeAt(i) ^ key.charCodeAt(i % keyLength));
        }

        return btoa(encrypted);
    }

    xorDecrypt(encryptedMessage) {
        const key = this.key;
        const keyLength = key.length;
        const messageLength = atob(encryptedMessage).length;
        let decrypted = '';

        for (let i = 0; i < messageLength; i++) {
            decrypted += String.fromCharCode(atob(encryptedMessage).charCodeAt(i) ^ key.charCodeAt(i % keyLength));
        }

        return decrypted;
    }

    rot13(message) {
        return message.replace(/[a-zA-Z]/g, function (c) {
            return String.fromCharCode((c <= "Z" ? 90 : 122) >= (c = c.charCodeAt(0) + 13) ? c : c - 26);
        });
    }
}

// Usage example:
/*
const key = "your_secret_key";
const cipher = new Cipher(key);
const message = "Hello, World!";
const encryptedMessage = cipher.encrypt(message);
const decryptedMessage = cipher.decrypt(encryptedMessage);

console.log("Original message: " + message);
console.log("Encrypted message: " + encryptedMessage);
console.log("Decrypted message: " + decryptedMessage);
*/