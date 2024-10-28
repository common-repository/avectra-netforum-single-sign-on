<?php

namespace NetAuth\Views;

use WP\Views\View;
use WP\Views\Page;

class NetforumHelp extends View {

    protected $fields = array();

    public function __construct() {
        $this->includePathMain();
    }

    private function includePathMain() {
        include_once Page::getTemplatesPath(__DIR__) . '/help.tpl';
    }

}
