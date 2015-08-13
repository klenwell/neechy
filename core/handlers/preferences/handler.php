<?php
/**
 * core/handlers/preferences/handler.php
 *
 * PreferencesHandler class.
 *
 */
require_once('../core/handlers/base.php');
require_once('../core/neechy/path.php');
require_once('../core/neechy/templater.php');
require_once('../core/handlers/preferences/php/validator.php');


class PreferencesHandler extends NeechyHandler {

    #
    # Public Methods
    #
    public function handle() {
        # Change password request
        if ( $this->request->action_is('change-password') ) {
            $update = new ChangePasswordValidator($this->request);
            if ( $update->successful() ) {
                $user = User::logged_in();
                $user->set_password($this->request->post('new-password'));
                if ( $user->save() ) {
                    $this->t->flash('Your password has been changed.', 'success');
                }
                else {
                    $this->t->flash('There was a problem saving your password.', 'danger');
                }
            }
            else {
                $this->t->data('validation-errors', $update->errors);
            }
            $content = $this->render_view('content');
        }

        # Default: display
        else {
            $content = $this->render_view('content');
        }

        return $content;
    }

    #
    # Private
    #
}
