<?php

namespace WP\Views;

abstract class Form {

    protected $wp;

    protected function store() {
        $post = $_POST[$this->page];
        array_walk_recursive($post, array($this,'sanitize'));
        //return add_option($this->page, $post) || update_option($this->page, $post);

                if (false!==get_option($this->page) ) 
                {
                
                    $result = update_option( $this->page, $post );
                
                } else {
                // option not exist
                    $result =  add_option($this->page, $post);
                }

               return $result;
    }

    protected function validate() {
        if (!isset($_POST[$this->page])) {
            return false;
        } 
        $post = $_POST[$this->page];
        $this->wp = new \WP_Error();

try{
        foreach ($this->fields as $key => $section) {
            $key = $this->toSlug($key);


            foreach ($section['fields'] as $field => $option) {
                $field = $this->toSlug($field);
                if (isset($option['required']) && $option['required']) {
                    if (!is_null($post[$key][$field]) && trim($post[$key][$field]) == '') {
                        $this->wp->add($field, __($option['title'] . ' cannot be left empty.'));
                        continue;
                    }
                } if (isset($option['validate']) && !is_null($option['validate'])) {
                    if (!is_array($option['validate'])) {
                        continue;
                    } if (!preg_match('/^' . current($option['validate']) . '$/is', $post[$key][$field])) {
                        $msg = end($option['validate']) != '' ? end($option['validate']) : 'must be valid characters.';
                        $this->wp->add($field, __($option['title'] . ' ' . $msg));
                        continue;
                    }
                }
            }
        } 
    }
          catch(Exception $e)
          {
            echo 'Error writing to database: ',  $e->getMessage(), "\n";
          }return sizeof($this->wp->get_error_codes()) <= 0;
    }

    protected function flash($error = false, $msg = null) {
        if ($error) {
            return printf('<div id="message" class="%s"><p><strong>%s</strong></p><p>%s</p></div>', $error ? 'error' : 'updated', $error ? __('Uh oh!') : __('Yay!'),
                    __(implode('<br>', $this->wp->get_error_messages())));
        }else{

        return printf('<div id="message" class="%s"><p><strong>%s</strong></p><p>%s</p></div>', 'updated', 'Yay!', is_null($msg) ? __('Your request has been processed successfully.') : __($msg));
        }
    }

    protected function sanitize($e) {
        return $e = trim(stripslashes(sanitize_text_field($e)));
    }

}