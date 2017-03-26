<?php
/**
 * core/neechy/request.php
 *
 * Neechy Request class.
 *
 */
require_once('../core/neechy/constants.php');
require_once('../core/neechy/errors.php');


class NeechyRequestError extends NeechyError {}


class NeechyRequest {
    #
    # Properties
    #
    static private $instance = null;

    public $route = null;
    public $handler = null;
    public $action = null;
    public $format = null;
    public $user = null;                 # User model object set by WebService

    private $route_params = array();     # array of path params parsed from route
    private $params = array();           # array of route params following handler/action
    private $query_params = array();     # associative array of query string params

    private $valid_formats = array('html', 'json', 'plain');

    #
    # Constructor
    #
    public function __construct() {
        $this->route = $this->compute_route();
        $this->route_params = $this->parse_route($this->route);
        $this->query_params = array_merge($_GET, $_POST);

        $this->handler = (count($this->route_params) > 0) ? $this->route_params[0] :
            DEFAULT_HANDLER;
        $this->action = (count($this->route_params) > 1) ? $this->route_params[1] :
            null;

        if ( count($this->route_params) > 2 ) {
            $this->params = array_slice($this->route_params, 2);
        }

        $this->format = $this->extract_format();
    }

    #
    # Static Public Methods
    #
    static public function load() {
        if (! is_null(self::$instance)) {
            return self::$instance;
        }
        else {
            self::$instance = new NeechyRequest();
            return self::$instance;
        }
    }

    #
    # Public Methods
    #
    public function param($index, $default=null) {
        return (isset($this->params[$index])) ? trim($this->params[$index]) : $default;
    }

    public function query($key, $default=null) {
        return (isset($this->query_params[$key])) ? trim($this->query_params[$key]) : $default;
    }

    public function post($key, $default=null) {
        return (isset($_POST[$key])) ? trim($_POST[$key]) : $default;
    }

    public function get($key, $default=null) {
        return (isset($_GET[$key])) ? trim($_GET[$key]) : $default;
    }

    #
    # Private Methods
    #
    private function compute_route() {
        // Web request should have REQUEST_URL set
        if ( isset($_SERVER['REQUEST_URI']) ) {
            return $_SERVER['REQUEST_URI'];
        }

        // Console request should have argv set
        if ( isset($_SERVER['argv']) ) {
            if ( count($_SERVER['argv']) > 1 ) {
                return $_SERVER['argv'][1];
            }
            else {
                return '';
            }
        }

        throw new NeechyRequestError('Unable to compute route.', 500);
    }

    private function parse_route($route) {
        $route_params = array();

        if ( substr_count($route, '?') > 0 ) {
            $route_part = explode('?', $route);
            $route = $route_part[0];
        }

        if ( substr_count($route, '/') < 1 ) {
            $route_params = array($route);
        }
        else {
            $route_params = explode('/', $route);
            $route_params = array_slice($route_params, 1);

            $last_index = count($route_params) - 1;
            if ( trim($route_params[$last_index]) == '' ) {
                unset($route_params[$last_index]);
            }
        }

        $route_params = array_map('urldecode', $route_params);
        return $route_params;
    }

    private function extract_format() {
        $format = $this->param('format');
        return (in_array($format, $this->valid_formats)) ? $format : 'html';
    }
}
