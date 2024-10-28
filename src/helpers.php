<?php

if (!function_exists('http_build_url')) {
    define('HTTP_URL_REPLACE', 1);
    define('HTTP_URL_JOIN_PATH', 2);
    define('HTTP_URL_JOIN_QUERY', 4);
    define('HTTP_URL_STRIP_USER', 8);
    define('HTTP_URL_STRIP_PASS', 16);
    define('HTTP_URL_STRIP_AUTH', 32);
    define('HTTP_URL_STRIP_PORT', 64);
    define('HTTP_URL_STRIP_PATH', 128);
    define('HTTP_URL_STRIP_QUERY', 256);
    define('HTTP_URL_STRIP_FRAGMENT', 512);
    define('HTTP_URL_STRIP_ALL', 1024);

    function http_build_url($wsdl_e, $parse_url_e = array(), $flags = HTTP_URL_REPLACE, &$sp3edb3d = false) {
        $keys = array('user','pass','port','path','query','fragment');
        if ($flags & HTTP_URL_STRIP_ALL) {
            $flags |= HTTP_URL_STRIP_USER;
            $flags |= HTTP_URL_STRIP_PASS;
            $flags |= HTTP_URL_STRIP_PORT;
            $flags |= HTTP_URL_STRIP_PATH;
            $flags |= HTTP_URL_STRIP_QUERY;
            $flags |= HTTP_URL_STRIP_FRAGMENT;
        } else {
            if ($flags & HTTP_URL_STRIP_AUTH) {
                $flags |= HTTP_URL_STRIP_USER;
                $flags |= HTTP_URL_STRIP_PASS;
            }
        } $parse_url = parse_url($wsdl_e);
        if (isset($parse_url_e['scheme'])) {
            $parse_url['scheme'] = $parse_url_e['scheme'];
        } if (isset($parse_url_e['host'])) {
            $parse_url['host'] = $parse_url_e['host'];
        } if ($flags & HTTP_URL_REPLACE) {
            foreach ($keys as $key) {
                if (isset($parse_url_e[$key])) {
                    $parse_url[$key] = $parse_url_e[$key];
                }
            }
        } else {
            if (isset($parse_url_e['path']) && $flags & HTTP_URL_JOIN_PATH) {
                if (isset($parse_url['path'])) {
                    $parse_url['path'] = rtrim(str_replace(basename($parse_url['path']), '', $parse_url['path']), '/') . '/' . ltrim($parse_url_e['path'], '/');
                } else {
                    $parse_url['path'] = $parse_url_e['path'];
                }
            } if (isset($parse_url_e['query']) && $flags & HTTP_URL_JOIN_QUERY) {
                if (isset($parse_url['query'])) {
                    $parse_url['query'] .= '&' . $parse_url_e['query'];
                } else {
                    $parse_url['query'] = $parse_url_e['query'];
                }
            }
        } foreach ($keys as $key) {
            if ($flags & (int) constant('HTTP_URL_STRIP_' . strtoupper($key))) {
                unset($parse_url[$key]);
            }
        } $sp3edb3d = $parse_url;
        return (isset($parse_url['scheme']) ? $parse_url['scheme'] . '://' : '') . (isset($parse_url['user']) ? $parse_url['user'] . (isset($parse_url['pass']) ? ':' . $parse_url['pass'] : '') . '@' : '') . (isset($parse_url['host'])
                    ? $parse_url['host'] : '') . (isset($parse_url['port']) ? ':' . $parse_url['port'] : '') . (isset($parse_url['path']) ? $parse_url['path'] : '') . (isset($parse_url['query']) ? '?' . $parse_url['query']
                    : '') . (isset($parse_url['fragment']) ? '#' . $parse_url['fragment'] : '');
    }

} if (!function_exists('colorize')) {

    function colorize($text, $status = 'blue') {
        $out = '';
        switch ($status) {
            case 'green': case 'SUCCESS': $out = '[42m';
                break;
            case 'red': case 'FAILURE': $out = '[41m';
                break;
            case 'yellow': case 'WARNING': $out = '[43m';
                break;
            case 'blue': case 'NOTE': $out = '[44m';
                break;
            default: throw new Exception('Invalid status: ' . $status);
        } return chr(27) . "{$out}" . "{$text}" . chr(27) . '[0m';
    }

} if (!function_exists('prettyXML')) {

    function prettyXML($xml) {
        if (!$xml || !class_exists('DomDocument')) {
            return $xml;
        } $spccb2ac = new \DomDocument('1.0');
        $spccb2ac->preserveWhiteSpace = false;
        $spccb2ac->formatOutput = true;
        $spccb2ac->loadXML($xml);
        return $spccb2ac->saveXML();
    }

} if (!function_exists('autoload_psr4')) {

    function autoload_psr4($className) {
        $namespace = $fileName = '';
        $extensions = array('.php','.class.php','.inc');
        $includePath = dirname(__FILE__);
        if (false !== ($lastNsPos = strripos($className, '\\'))) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        } $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className);
        $fullFileName = $includePath . DIRECTORY_SEPARATOR . $fileName;
        foreach ($extensions as $ext) {
            if (file_exists($fullFileName . $ext)) {
                require_once $fullFileName . $ext;
            }
        }
    }

} if (!function_exists('autoload_psr0')) {

    function autoload_psr0($className) {
        $extensions = array('.php','.class.php','.inc');
        $thisClass = str_replace(__NAMESPACE__ . '\\', '', __CLASS__);
        $baseDir = realpath(__DIR__) . DIRECTORY_SEPARATOR;
        if (substr($baseDir, -strlen($thisClass)) === $thisClass) {
            $baseDir = substr($baseDir, 0, -strlen($thisClass));
        } $className = ltrim($className, '\\');
        $fileName = $baseDir;
        $namespace = '';
        if ($lastNsPos = strripos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName .= str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        } $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className);
        foreach ($extensions as $ext) {
            if (file_exists($fileName . $ext)) {
                require_once $fileName . $ext;
            }
        }
    }

} if (!function_exists('registerAutoloader')) {

    function registerAutoloader($n = 'psr4') {
        spl_autoload_register('autoload_' . $n);
    }

} if (!function_exists('dd')) {

    function dd($e, $q = false) {
        $break = php_sapi_name() == 'cli' ? '
' : '<br>
';
        if (is_string($e)) {
            echo $e . $break;
            if ($q) {
                die;
            } return;
        } if (php_sapi_name() == 'cli') {
            print_r($e);
        } else {
            echo '<pre>';
            print_r($e);
            echo '</pre>';
        } if ($q) {
            die;
        }
    }

} if (!function_exists('printfa')) {

    function printfa($format, $arr) {
        return call_user_func_array('printf', array_merge((array) $format, $arr));
    }

} if (!function_exists('camel_case')) {

    function camel_case($value) {
        $camelCahe = array();
        if (isset($camelCahe[$value])) {
            return $camelCahe[$value];
        } return $camelCahe[$value] = lcfirst(studly($value));
    }

} if (!function_exists('studly')) {

    function studly($value) {
        $camelCahe = array();
        $key = $value;
        if (isset($camelCahe[$key])) {
            return $camelCahe[$key];
        } $value = ucwords(str_replace(array('-','_'), ' ', $value));
        return $camelCahe[$key] = str_replace(' ', '', $value);
    }

} if (!function_exists('snake_case')) {

    function snake_case($value, $studlyUnderscore = '_') {
        $studlyCache = array();
        $key = $value . $studlyUnderscore;
        if (isset($studlyCache[$key])) {
            return $studlyCache[$key];
        } if (!ctype_lower($value)) {
            $value = strtolower(preg_replace('/(.)(?=[A-Z])/', '$1' . $studlyUnderscore, $value));
        } return $studlyCache[$key] = $value;
    }

} if (!function_exists('starts_with')) {

    function starts_with($haystack, $needles) {
        foreach ((array) $needles as $needle) {
            if ($needle != '' && strpos($haystack, $needle) === 0) {
                return true;
            }
        } return false;
    }

} if (!function_exists('contains')) {

    function contains($haystack, $needles) {
        foreach ((array) $needles as $needle) {
            if ($needle != '' && strpos($haystack, $needle) !== false) {
                return true;
            }
        } return false;
    }

} if (!function_exists('ends_with')) {

    function ends_with($haystack, $needles) {
        foreach ((array) $needles as $needle) {
            if ((string) $needle === substr($haystack, -strlen($needle))) {
                return true;
            }
        } return false;
    }

} if (!function_exists('finish')) {

    function finish($value, $cap) {
        $quoted = preg_quote($cap, '/');
        return preg_replace('/(?:' . $quoted . ')+$/', '', $value) . $cap;
    }

} if (!function_exists('is')) {

    function is($pattern, $value) {
        if ($pattern == $value) {
            return true;
        } $pattern = preg_quote($pattern, '#');
        $pattern = str_replace('\\*', '.*', $pattern) . '\\z';
        return (bool) preg_match('#^' . $pattern . '#', $value);
    }

}