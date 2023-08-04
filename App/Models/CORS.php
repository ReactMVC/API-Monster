<?php

namespace Monster\App\Models;

class CORS
{
    // Initialize private properties to store CORS settings
    private $allowedOrigins = array();
    private $allowedMethods = array();
    private $allowedHeaders = array();
    private $exposedHeaders = array();
    private $maxAge = 0;
    private $allowCredentials = false;

    // Constructor to set CORS settings when creating a new instance of the class
    public function __construct($allowedOrigins = array(), $allowedMethods = array(), $allowedHeaders = array(), $exposedHeaders = array(), $maxAge = 0, $allowCredentials = false)
    {
        // Set the allowed origins, methods, headers, exposed headers, max age, and allow credentials properties
        $this->allowedOrigins = $allowedOrigins;
        $this->allowedMethods = $allowedMethods;
        $this->allowedHeaders = $allowedHeaders;
        $this->exposedHeaders = $exposedHeaders;
        $this->maxAge = $maxAge;
        $this->allowCredentials = $allowCredentials;
    }

    // Method to set CORS headers based on the properties set in the constructor
    public function setHeaders()
    {
        // Check if the allowed origins include all origins by checking if '*' is in the array
        if (in_array('*', $this->allowedOrigins)) {
            // Allow all origins with a wildcard
            header('Access-Control-Allow-Origin: *');
        } else {
            // Check if the origin of the request is in the allowed origins array
            $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
            if (in_array($origin, $this->allowedOrigins)) {
                // Set the allowed origin to the origin of the request
                header("Access-Control-Allow-Origin: $origin");
            }
        }
        // Check if credentials are allowed and set the allow credentials header if true
        if ($this->allowCredentials) {
            header('Access-Control-Allow-Credentials: true');
        }
        // Set the exposed headers header if there are any exposed headers
        if (!empty($this->exposedHeaders)) {
            header('Access-Control-Expose-Headers: ' . implode(', ', $this->exposedHeaders));
        }
        // Set the max age header if the max age is greater than 0
        if ($this->maxAge > 0) {
            header("Access-Control-Max-Age: $this->maxAge");
        }
        // Set the allowed methods header if there are any allowed methods
        if (!empty($this->allowedMethods)) {
            header('Access-Control-Allow-Methods: ' . implode(', ', $this->allowedMethods));
        }
        // Set the allowed headers header if there are any allowed headers
        if (!empty($this->allowedHeaders)) {
            header('Access-Control-Allow-Headers: ' . implode(', ', $this->allowedHeaders));
        }
    }
}