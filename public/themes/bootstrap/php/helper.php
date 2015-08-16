<?php
/**
 * public/themes/bootstrap/php/helper.php
 *
 * Base Neechy Helper class
 *
 */
require_once('../core/neechy/helper.php');


class BootstrapHelper extends NeechyHelper {

    #
    # Properties
    #
    public $request = null;

    #
    # Constructor
    #
    public function __construct($request=NULL) {
        $this->request = $request;
    }

    #
    # Public Methods
    #
    public function open_form($url, $method='POST', $attrs=array(),
                              $hidden_fields=array()) {
        $format = '<form role="form" method="%s" action="%s"%s />';
        $bootstrap_class = 'form-login';

        if ( isset($attrs['class']) ) {
            $attrs['class'] = sprintf('%s %s', $bootstrap_class, $attrs['class']);
        }
        else {
            $attrs['class'] = $bootstrap_class;
        }

        $attr_string = $this->array_to_attr_string($attrs);
        $form_tag = sprintf($format, $method, $url, $attr_string);

        # Add CSRF token for POST forms
        if (strtoupper($method) == 'POST') {
            $hidden_fields['csrf_token'] = $_SESSION['csrf_token'];
        }

        $hidden_tags = array();
        foreach ( $hidden_fields as $field => $value ) {
            $hidden_tags[] = $this->input_field('hidden', $field, $value);
        }

        if ( $hidden_tags ) {
            $hidden_tag_list = implode("\n", $hidden_tags);
            $form_tag = implode("\n", array($form_tag, $hidden_tag_list));
        }

        return $form_tag;
    }

    public function submit_button($label, $state='primary', $attrs=array()) {
        $format = '<button type="submit" %s>%s</button>';
        $state_class = sprintf('btn-%s', $state);
        $bootstrap_class = sprintf('btn btn-lg %s btn-block', $state_class);

        if ( isset($attrs['class']) ) {
            $attrs['class'] = sprintf('%s %s', $bootstrap_class, $attrs['class']);
        }
        else {
            $attrs['class'] = $bootstrap_class;
        }

        $optional_attrs = $this->array_to_attr_string($attrs);
        return sprintf($format, $optional_attrs, $label);
    }

    public function form_group($inner_html, $errors=array()) {
        # Set group class
        $validation_class = ( $errors ) ? 'has-error' : '';
        $group_class = sprintf('form-group %s', $validation_class);

        # Add feedback for errors
        $help_spans = array();

        if ( $errors ) {
            foreach ( $errors as $message ) {
                $help_spans[] = sprintf('<span class="help-block">%s</span>', $message);
            }
        }

        $format = <<<HTML5
<div class="%s">
  %s
  %s
</div>
HTML5;

        return sprintf($format, $group_class, $inner_html, join("\n", $help_spans));
    }
}