<?php

namespace Monster\App\Models;

class CORS
{
    // Define private static properties to store CORS settings
    static private $allowedOrigins = array();
    static private $allowedMethods = array();
    static private $allowedHeaders = array();
    static private $exposedHeaders = array();
    static private $maxAge = 0;
    static private $allowCredentials = false;

    // Method to set allowed origins
    public static function origin($origins)
    {
        // Assign passed origins to the static property
        self::$allowedOrigins = $origins;
        // Return new instance of the class for method chaining
        return new static;
    }

    // Method to set allowed methods
    public static function methods($methods)
    {
        // Assign passed methods to the static property
        self::$allowedMethods = $methods;
        // Return new instance of the class for method chaining
        return new static;
    }

    // Method to set allowed headers
    public static function headers($headers)
    {
        // Assign passed headers to the static property
        self::$allowedHeaders = $headers;
        // Return new instance of the class for method chaining
        return new static;
    }

    // Method to set exposed headers
    public static function expose($headers)
    {
        // Assign passed headers to the static property
        self::$exposedHeaders = $headers;
        // Return new instance of the class for method chaining
        return new static;
    }

    // Method to set max age
    public static function maxAge($age)
    {
        // Assign passed age to the static property
        self::$maxAge = $age;
        // Return new instance of the class for method chaining
        return new static;
    }

    // Method to set allow credentials
    public static function credentials($credentials)
    {
        // Assign passed credentials to the static property
        self::$allowCredentials = $credentials;
        // Return new instance of the class for method chaining
        return new static;
    }

    // Method to set CORS headers based on the properties set in the constructor
    public static function setHeaders()
    {
        // Check if the allowed origins include all origins by checking if '*' is in the array
        if (in_array('*', self::$allowedOrigins)) {
            // Allow all origins with a wildcard
            header('Access-Control-Allow-Origin: *');
        } else {
            // Check if the origin of the request is in the allowed origins array
            $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
            if (in_array($origin, self::$allowedOrigins)) {
                // Set the allowed origin to the origin of the request
                header("Access-Control-Allow-Origin: $origin");
            }
        }
        // Check if credentials are allowed and set the allow credentials header if true
        if (self::$allowCredentials) {
            header('Access-Control-Allow-Credentials: true');
        }
        // Set the exposed headers header if there are any exposed headers
        if (!empty(self::$exposedHeaders)) {
            header('Access-Control-Expose-Headers: ' . implode(', ', self::$exposedHeaders));
        }
        // Set the max age header if the max age is greater than 0
        if (self::$maxAge > 0) {
            header("Access-Control-Max-Age: " . self::$maxAge);
        }
        // Set the allowed methods header if there are any allowed methods
        if (!empty(self::$allowedMethods)) {
            header('Access-Control-Allow-Methods: ' . implode(', ', self::$allowedMethods));
        }
        // Set the allowed headers header if there are any allowed headers
        if (!empty(self::$allowedHeaders)) {
            header('Access-Control-Allow-Headers: ' . implode(', ', self::$allowedHeaders));
        }
    }
}