<?php

namespace Monster\App\Models;

/**
 * The Env class is responsible for reading and parsing environment variables from a file.
 */
class Env
{
    /**
     * @var array Holds the environment data after parsing.
     */
    private $envData;

    /**
     * Constructor for the Env class.
     *
     * @param string $envFilePath The file path to the .env file.
     */
    public function __construct($envFilePath)
    {
        // Read and store the environment variables from the file.
        $this->envData = $this->readEnvFile($envFilePath);
    }

    /**
     * Reads the environment file and parses the variables into an array.
     *
     * @param string $envFilePath The file path to the .env file.
     * @return array The parsed environment variables.
     */
    private function readEnvFile($envFilePath)
    {
        // Initialize an empty array to hold the environment data.
        $envData = array();
        // Open the file for reading.
        $file = fopen($envFilePath, "r");
        if ($file) {
            // Read each line of the file.
            while (($line = fgets($file)) !== false) {
                // Skip lines that are comments or empty.
                if (preg_match("/^\s*(#|$)/", $line)) {
                    continue;
                }
                // Match lines that look like key=value pairs.
                if (preg_match("/^([^=]+)=(.*)$/", $line, $matches)) {
                    // Trim whitespace around the key.
                    $key = trim($matches[1]);
                    // Trim whitespace and quotes around the value.
                    $value = trim($matches[2], " \n\r\t");
                    // Check if the value is an array (starts with [ and ends with ]).
                    if (preg_match("/^\[(.*)\]$/", $value, $arrayMatches)) {
                        // Split the value by comma and trim each element.
                        $arrayValues = array_map(function ($item) {
                            return trim($item, "\" \t\n\r");
                        }, explode(',', trim($arrayMatches[1])));
                        // Assign the array to the key in the environment data.
                        $envData[$key] = $arrayValues;
                    } else {
                        // Assign the value to the key in the environment data.
                        $envData[$key] = trim($value, "\"");
                    }
                }
            }
            // Close the file after reading.
            fclose($file);
        }
        // Return the parsed environment data.
        return $envData;
    }

    /**
     * Retrieves the value of an environment variable by key.
     *
     * @param string $key The key of the environment variable to retrieve.
     * @return mixed|null The value of the environment variable, or null if not found.
     */
    public function get($key)
    {
        // Check if the key exists in the environment data and return its value.
        if (isset($this->envData[$key])) {
            return $this->envData[$key];
        }
        // Return null if the key does not exist.
        return null;
    }
}