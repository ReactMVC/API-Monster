<?php

namespace Monster\App\Models;

class Env
{
    private $envData;

    public function __construct($envFilePath)
    {
        $this->envData = $this->readEnvFile($envFilePath);
    }

    // Read the environment file and parse its contents into an associative array
    private function readEnvFile($envFilePath)
    {
        $envData = array(); // Initialize an empty array to store the environment data
        $file = fopen($envFilePath, "r"); // Open the environment file for reading
        if ($file) {
            while (($line = fgets($file)) !== false) {
                // Ignore lines starting with # or empty lines
                if (preg_match("/^\s*(#|$)/", $line)) {
                    continue;
                }
                // Parse lines in the format KEY=VALUE
                if (preg_match("/^([^=]+)=(.*)$/", $line, $matches)) {
                    $envData[$matches[1]] = $matches[2]; // Store the key-value pair in the $envData array
                }
            }
            fclose($file); // Close the environment file
        }
        return $envData; // Return the parsed environment data
    }

    // Retrieve the value of a specific key from the environment data
    public function get($key)
    {
        if (isset($this->envData[$key])) {
            return trim($this->envData[$key], "\""); // Remove surrounding double quotes if present
        }
        return null; // Return null if the key is not found
    }
}