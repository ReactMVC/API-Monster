<?php

namespace Monster\App\Models;

/**
 * Http - A simple PHP class for making HTTP requests using cURL
 */
class Http
{
    private $ch;
    private $options = [];

    /**
     * Constructor - initializes a new cURL handle and sets default options
     */
    public function __construct()
    {
        $this->ch = curl_init();
        $this->setDefaults();
    }

    /**
     * Header - adds a single HTTP header to the request
     *
     * @param string $header - the header to add
     * @return Http - returns the Http object for chaining
     */
    public function Header($header)
    {
        $this->options[CURLOPT_HTTPHEADER][] = $header;
        return $this;
    }

    /**
     * Headers - sets the HTTP headers for the request
     *
     * @param array $headers - an array of headers to set
     * @return Http - returns the Http object for chaining
     */
    public function Headers($headers)
    {
        $this->options[CURLOPT_HTTPHEADER] = $headers;
        return $this;
    }

    /**
     * Option - sets a cURL option for the request
     *
     * @param int $option - the option to set
     * @param mixed $value - the value to set the option to
     * @return Http - returns the Http object for chaining
     */
    public function Option($option, $value)
    {
        curl_setopt($this->ch, $option, $value);
        return $this;
    }

    /**
     * Timeout - sets the request timeout in seconds
     *
     * @param int $timeout - the timeout in seconds
     * @return Http - returns the Http object for chaining
     */
    public function Timeout($timeout)
    {
        $this->Option(CURLOPT_TIMEOUT, $timeout);
        return $this;
    }

    /**
     * Url - sets the URL for the request
     *
     * @param string $url - the URL to set
     * @return Http - returns the Http object for chaining
     */
    public function Url($url)
    {
        $this->Option(CURLOPT_URL, $url);
        return $this;
    }

    /**
     * Method - sets the HTTP method for the request
     *
     * @param string $method - the HTTP method to set (e.g. "GET", "POST", etc.)
     * @return Http - returns the Http object for chaining
     */
    public function Method($method)
    {
        $this->Option(CURLOPT_CUSTOMREQUEST, strtoupper($method));
        return $this;
    }

    /**
     * Body - sets the request body for the request
     *
     * @param mixed $body - the request body to set
     * @return Http - returns the Http object for chaining
     */
    public function Body($body)
    {
        $this->Option(CURLOPT_POSTFIELDS, $body);
        return $this;
    }

    /**
     * Send - sends the HTTP request and returns the response
     *
     * @return string - the response from the server
     * @throws Exception - if cURL encounters an error while executing the request
     */
    public function Send()
    {
        curl_setopt_array($this->ch, $this->options);
        $response = curl_exec($this->ch);
        if ($response === false) {
            throw new \Exception(curl_error($this->ch), curl_errno($this->ch));
        }
        return $response;
    }

    /**
     * getStatus - returns the HTTP status code of the response
     *
     * @return int - the HTTP status code
     */
    public function getStatus()
    {
        return curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
    }

    /**
     * setDefaults - sets some default cURL options for the request
     *
     * @return void
     */
    private function setDefaults()
    {
        $defaults = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_ENCODING => '',
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        ];
        curl_setopt_array($this->ch, $defaults);
    }

    /**
     * Destructor - closes the cURL handle when the object is destroyed
     */
    public function __destruct()
    {
        curl_close($this->ch);
    }
}