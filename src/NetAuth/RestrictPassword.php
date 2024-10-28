<?php

namespace NetAuth;

class RestrictPassword {

    public function __construct() {
        add_filter('show_password_fields', array($this,'disable'));
        add_filter('allow_password_reset', array($this,'disable'));
        add_filter('gettext', array($this,'remove'));
    }

    public function disable() {
        if (is_admin()) {
            $data = wp_get_current_user();
            $user = new \WP_User($data->ID);
            return !empty($user->roles) && is_array($user->roles) && array_shift($user->roles) == 'administrator';
        } return false;
    }

    public function remove($e) {
        return preg_replace('/lost your password\\??/is', '', $e);
    }

}
