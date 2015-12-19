<?php
namespace Pecee\Http;

class Request {

    protected static $instance;

    protected $data;
    protected $uri;
    protected $host;
    protected $method;
    protected $headers;

    /**
     * Return new instance
     * @return static
     */
    public static function getInstance() {
        if(self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function __construct() {
        $this->data = array();
        $this->host = $_SERVER['HTTP_HOST'];
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->method = (isset($_POST['_method'])) ? strtolower($_POST['_method']) : strtolower($_SERVER['REQUEST_METHOD']);
        $this->headers = $this->getAllHeaders();
    }

    public function getAllHeaders() {
        $headers = array();
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) === 'HTTP_') {
                $headers[strtolower(str_replace('_', '-', substr($name, 5)))] = $value;
            }
        }
        return $headers;
    }

    public function getIsSecure() {
        return isset($_SERVER['HTTPS']) ? true : (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] === 443);
    }

    /**
     * @return string
     */
    public function getUri() {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getHost() {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getMethod() {
        return $this->method;
    }

    /**
     * Get http basic auth user
     * @return string|null
     */
    public function getUser() {
        return (isset($_SERVER['PHP_AUTH_USER'])) ? $_SERVER['PHP_AUTH_USER']: null;
    }

    /**
     * Get http basic auth password
     * @return string|null
     */
    public function getPassword() {
        return (isset($_SERVER['PHP_AUTH_PW'])) ? $_SERVER['PHP_AUTH_PW']: null;
    }

    /**
     * Get headers
     * @return array
     */
    public function getHeaders() {
        return $this->headers;
    }

    /**
     * Get id address
     * @return string
     */
    public function getIp() {
        return isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Get referer
     * @return string
     */
    public function getReferer() {
        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    }

    /**
     * Get user agent
     * @return string
     */
    public function getUserAgent() {
        return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    }

    /**
     * Get header value by name
     * @param string $name
     * @return string|null
     */
    public function getHeader($name) {
        return (isset($this->headers[strtolower($name)])) ? $this->headers[strtolower($name)] : null;
    }

    /**
     * Get request input or default value
     * @param string $name
     * @param string $defaultValue
     * @return mixed
     */
    public function getInput($name, $defaultValue) {
        return (isset($_REQUEST[$name]) ? $_REQUEST[$name] : $defaultValue);
    }

    public function __set($name, $value = null) {
        $this->data[$name] = $value;
    }

    public function __get($name) {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

}