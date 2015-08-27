<?php
/**
 * core/neechy/config.php
 *
 * Neechy config module.
 *
 */
require_once('../core/neechy/path.php');


class NeechyConfig {
    #
    # Constants
    #
    const CORE_PATH = 'core/config/core.conf.php';
    const STUB_PATH = 'core/handlers/install/php/stub.config.php';
    const APP_PATH = 'config/app.conf.php';

    #
    # Properties
    #
    static private $config = array();

    public $path = '';
    private $settings = array();

    #
    # Public Static Methods
    #
    static public function init($path=null) {
        $core_settings = self::load_core_config_file();
        $app_settings = self::load_app_config_file($path);
        self::$config = array_merge($core_settings, $app_settings);
    }

    static public function load_app_config($path=null) {
        $app_config = new NeechyConfig();
        $app_config->path = self::app_config_path();
        $app_config->settings = self::load_app_config_file($path);
        return $app_config;
    }

    static public function get($setting, $default=null) {
        if ( isset(self::$config[$setting]) ) {
            return self::$config[$setting];
        }
        else {
            return $default;
        }
    }

    static public function app_config_path() {
        return NeechyPath::join(NEECHY_ROOT, self::APP_PATH);
    }

    #
    # Private Static Methods
    #
    static private function load_core_config_file($path=null) {
        $path = NeechyPath::join(NEECHY_ROOT, self::CORE_PATH);
        require($path);
        return $neechy_core_config;
    }

    static private function load_app_config_file($path=null) {
        # Detect whether initial install needed
        $requires_install = (is_null($path)) && (! self::app_config_file_exists());
        if ( $requires_install ) {
            self::install_app_config_file();
        }

        $path = ( $path ) ? $path : self::app_config_path();
        require($path);
        return $neechy_config;
    }

    static private function app_config_file_exists() {
        $app_config_path = self::app_config_path();
        return file_exists($app_config_path);
    }

    static private function install_app_config_file($sleep=2) {
        $app_config_dir = dirname(self::app_config_path());
        if ( ! file_exists($app_config_dir) ) {
            mkdir($app_config_dir);
        }

        $app_config_path = self::app_config_path();
        $warning = sprintf("\n[WARNING] Creating user config file: %s\n\n", $app_config_path);
        echo $warning;

        $stub_config_path = NeechyPath::join(NEECHY_ROOT, self::STUB_PATH);
        copy($stub_config_path, $app_config_path);
    }

    #
    # Instance Methods
    #
    public function update_setting($setting, $value) {
        $this->settings[$setting] = $value;
    }

    public function save() {
        $format = <<<HEREPHP
<?php
/**
 * Neechy App Configuration File
 *
 * This file was generated by the InstallHandler installer on %s
 *
 */

%s = array(
    %s
);
HEREPHP;

        $config_lines = array();
        foreach ( $this->settings as $setting => $value ) {
            $config_lines[] = sprintf("'%s' => '%s',",
                str_replace("'", "/'", $setting),
                str_replace("'", "/'", $value)
            );
        }

        sort($config_lines);

        $content = sprintf($format,
            date('r'),
            '$neechy_config',
            implode("\n    ", $config_lines)
        );

        # Write file
        $file = @fopen($this->path, "w");
        fwrite($file, $content);
        fclose($file);
    }

    public function reload() {
        NeechyDatabase::disconnect_from_db();
        NeechyConfig::init($this->path);
    }
}
