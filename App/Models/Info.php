<?php

namespace Monster\App\Models;

/*
This is the Info class which contains various methods to retrieve information about the user and the server.
*/

class Info
{

    /*
        Retrieves the domain name of the server.
        @return string The domain name of the server.
     */
    public static function Domain()
    {
        return $_SERVER['SERVER_NAME'];
    }

    /*
        Retrieves the host name used in the HTTP request.
        @return string The host name used in the HTTP request.
     */
    public static function Host()
    {
        return $_SERVER['HTTP_HOST'];
    }

    /*
        Retrieves the IP address of the server's host.
        @return string The IP address of the server's host.
     */
    public static function HostIP()
    {
        return gethostbyname($_SERVER['SERVER_NAME']);
    }

    /*
        Retrieves the IP address of the user making the request.
        @return string The IP address of the user making the request.
     */
    public static function UserIP()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    /*
        Retrieves the cookies sent by the user.
        @return array An associative array of cookies sent by the user.
     */
    public static function Cookies()
    {
        return $_COOKIE;
    }

    /*
        Retrieves the full cookie string sent by the user.
        @return string The full cookie string sent by the user.
     */
    public static function FullCookie()
    {
        return $_SERVER['HTTP_COOKIE'];
    }

    /*
        Retrieves the path of the current request.
        @return string The path of the current request.
     */
    public static function Path()
    {
        return $_SERVER['REQUEST_URI'];
    }

    /*
        Retrieves the type of device used by the user.
        @return string The type of device used by the user (Mobile, Tablet, or Desktop).
     */
    public static function Device()
    {
        $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
        if (strpos($userAgent, 'mobile') !== false) {
            return 'Mobile';
        } elseif (strpos($userAgent, 'tablet') !== false) {
            return 'Tablet';
        } else {
            return 'Desktop';
        }
    }

    /*
        Retrieves the user agent string sent by the user.
        @return string The user agent string sent by the user.
     */
    public static function UserAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    /*
        Retrieves the HTTP headers sent by the user.
        @return array An associative array of HTTP headers sent by the user.
     */
    public static function getHeaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (strpos($name, 'HTTP') === 0) {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }

    /*
        Echoes the cookies sent by the user.
     */
    public static function echoCookies()
    {
        $cookies = $_COOKIE;
        foreach ($cookies as $key => $value) {
            echo $key . ': ' . $value . '<br>';
        }
    }

    /*

        Retrieves the operating system of the user.

        @return string The operating system of the user.
     */
    public static function getOS()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $devices = [
            'Windows Phone' => 'Windows Phone',
            'iPhone' => 'iPhone',
            'iPad' => 'iPad',
            'Kindle' => 'Silk',
            'Android' => 'Android',
            'PlayBook' => 'PlayBook',
            'BlackBerry' => 'BlackBerry',
            'Macintosh' => 'Macintosh',
            'Linux' => 'Linux',
            'Windows' => 'Windows'
        ];

        foreach ($devices as $os => $device) {
            if (strpos($userAgent, $device) !== false) {
                return $os;
            }
        }

        return 'Unknown';
    }
}
