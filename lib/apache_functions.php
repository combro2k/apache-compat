<?php
if (!function_exists('apache_request_headers')) {
    function apache_request_headers()
    {
        $arh = array();
        $rx_http = '/\AHTTP_/';

        foreach ($_SERVER as $key => $val) {
            if (preg_match($rx_http, $key)) {
                $arh_key = preg_replace($rx_http, '', $key);
                $rx_matches = array();
                // do some nasty string manipulations to restore the original letter case
                // this should work in most cases
                $rx_matches = explode('_', strtolower($arh_key));
                if (count($rx_matches) > 0 and strlen($arh_key) > 2) {
                    foreach ($rx_matches as $ak_key => $ak_val)
                        $rx_matches[$ak_key] = ucfirst($ak_val);
                    $arh_key = implode('-', $rx_matches);
                }
                $arh[$arh_key] = $val;
            }
        }

        if (isset($_SERVER['CONTENT_TYPE'])) {
            $arh['Content-Type'] = $_SERVER['CONTENT_TYPE'];
        }
        if (isset($_SERVER['CONTENT_LENGTH'])) {
            $arh['Content-Length'] = $_SERVER['CONTENT_LENGTH'];
        }

        return ($arh);
    }
}

if (!function_exists('getallheaders')) {
    /**
     * Get all HTTP header key/values as an associative array for the current request.
     *
     * @return string[string] The HTTP header key/value pairs.
     *
     * Copied from https://github.com/ralouphie/getallheaders
     * @author ralouphie
     */
    function getallheaders()
    {
        $headers = array();
        $copy_server = array(
            'CONTENT_TYPE' => 'Content-Type',
            'CONTENT_LENGTH' => 'Content-Length',
            'CONTENT_MD5' => 'Content-Md5',
        );
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) === 'HTTP_') {
                $key = substr($key, 5);
                if (!isset($copy_server[$key]) || !isset($_SERVER[$key])) {
                    $key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', $key))));
                    $headers[$key] = $value;
                }
            } elseif (isset($copy_server[$key])) {
                $headers[$copy_server[$key]] = $value;
            }
        }
        if (!isset($headers['Authorization'])) {
            if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                $headers['Authorization'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
            } elseif (isset($_SERVER['PHP_AUTH_USER'])) {
                $basic_pass = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
                $headers['Authorization'] = 'Basic ' . base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $basic_pass);
            } elseif (isset($_SERVER['PHP_AUTH_DIGEST'])) {
                $headers['Authorization'] = $_SERVER['PHP_AUTH_DIGEST'];
            }
        }
        return $headers;
    }
}

if (!function_exists("apache_child_terminate")) {
    function apache_child_terminate()
    {
        register_shutdown_function("killonexit");
    }

    function killonexit()
    {
        @exec("kill " . getmypid());
    }
}

if (!function_exists('apache_get_modules')) {
    function apache_get_modules($zend_extensions = false)
    {
        return get_loaded_extensions($zend_extensions);
    }
}

if (!function_exists('apache_get_version')) {
    function apache_get_version()
    {
        return false;
    }
}

if (!function_exists('apache_getenv')) {
    function apache_getenv($variable, $walk_to_top = false)
    {
        return getenv($variable);
    }
}

if (!function_exists('apache_response_headers')) {
    function apache_response_headers () {
        $arh = array();
        $headers = headers_list();
        foreach ($headers as $header) {
            $header = explode(":", $header);
            $arh[array_shift($header)] = trim(implode(":", $header));
        }
        return $arh;
    }
}