<?php
/**
 * core/handlers/base.php
 *
 * NeechyHandler base class.
 *
 */
require_once('../core/neechy/constants.php');
require_once('../core/neechy/templater.php');
require_once('../core/neechy/path.php');
require_once('../core/neechy/errors.php');
require_once('../core/models/page.php');



class NeechyHandler {
    #
    # Properties
    #
    public $request = null;
    public $page = null;
    public $t = null;
    public $is_console = false;

    #
    # Constructor
    #
    public function __construct($request=null, $page=null) {
        $this->request = $request;
        $this->page = $page;
        $this->t = NeechyTemplater::load();
        $this->t->data('handler', get_class($this));
    }

    #
    # Public Methods
    #
    public function handle() {
        throw new NeechyError('NeechyHandler::handler should be overridden');
    }

    public function render_view($id_or_path) {
        # First look for partial file in handler's html folder. If not there,
        # user theme's html folder.
        $fname = sprintf('%s.html.php', $id_or_path);
        $handler_view = NeechyPath::join($this->html_path(), $fname);

        if ( file_exists($handler_view) ) {
            return $this->t->render_partial_by_path($handler_view);
        }
        elseif ( file_exists($id_or_path) ) {
            return $this->t->render_partial_by_path($id_or_path);
        }
        else {
            return $this->t->render_partial_by_id($id_or_path);
        }
    }

    public function redirect($handler, $page=null) {
        # Convenient wrapper for NeechyResponse::redirect. Also allows mocking for
        # testing, which is difficult with static methods. (See
        # http://stackoverflow.com/q/2357001/1093087).
        $url = NeechyPath::url($page, $handler);
        return NeechyResponse::redirect($url);
    }

    #
    # Protected Methods
    #
    protected function html_path() {
        return NeechyPath::join(NEECHY_HANDLER_CORE_PATH, $this->folder_name(), 'html');
    }

    protected function folder_name() {
        $class_name = get_class($this);
        $folder_name = str_replace('Handler', '', $class_name);
        return strtolower($folder_name);
    }

    #
    # Print Functions
    #
    protected function prompt_user($prompt) {
        print($prompt);
        $stdin = fopen('php://stdin', 'r');
        $response = fgets($stdin);
        fclose($stdin);
        return trim($response);
    }

    protected function println($message) {
        printf("%s\n", $message);
    }

    protected function print_header($message) {
        $console_f = "\n*** %s";
        $html_f = <<<XHTML
    <div class="row">
      <div class="col-md-4"><h4 class="section">%s</h4></div>
    </div>
XHTML;

        if ( $this->is_console ) {
            $this->println(sprintf($console_f, $message));
        }
        else {
            $this->html_report[] = sprintf($html_f, $message);
        }
    }

    protected function print_error($e) {
        $message = ($e instanceof Exception) ? $e->getMessage() : (string) $e;

        if ( $this->is_console ) {
            $format = "\n\nERROR:\n%s\n";
            $this->println(sprintf($format, $message));
            die(1);
        }
        else {
            throw $e;
        }
    }
}
