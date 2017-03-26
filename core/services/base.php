<?php
/**
 * core/services/base.php
 *
 * NeechyService base class.
 *
 */
require_once('../core/neechy/constants.php');
require_once('../core/neechy/config.php');


class NeechyService {
    #
    # Properties
    #
    public $type = 'base';
    public $config = null;
    public $request = null;

    #
    # Constructor
    #
    public function __construct($config) {
        $this->config = $config;
        $this->request = new NeechyRequest();
    }

    #
    # Public Methods
    #
    public function serve() {
    }

    public function serve_error() {
    }

    public function is($type) {
        return $this->type === $type;
    }
}
