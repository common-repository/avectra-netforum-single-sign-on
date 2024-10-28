<?php

namespace NetAuth;

class RemoveAdminBar {

    private $menuItems = array('Dashboard');

    public function __construct() {
        add_filter('after_setup_theme', array($this,'removeAdminBar'));
        add_action('admin_menu', array($this,'removeAdminMenuItems'));
    }

    private function hasPermission() {
        return !current_user_can('manage_options');
    }

    public function removeAdminBar() {
        if (!$this->hasPermission()) {
            return false;
        } show_admin_bar(false);
        add_filter('show_admin_bar', '__return_false');
        add_filter('wp_admin_bar_class', '__return_false');
    }

    public function removeAdminMenuItems() {
        global $menu;
        if (!$this->hasPermission()) {
            return false;
        } $remove = array_filter($this->menuItems, '__');
        end($menu);
        while (prev($menu)) {
            $item = explode(' ', $menu[key($menu)][0]);
            if (in_array($item[0] != null ? $item[0] : '', $remove)) {
                unset($menu[key($menu)]);
            }
        } if (preg_match('/src\\s*=\\s*(\'|")(.*?)("|\')/', get_avatar(get_current_user_id(), 20), $m)) {
            $menu[] = array('Log Out','read',wp_logout_url(),'Logout','menu-top','logout',$m[2]);
        }
    }

}
