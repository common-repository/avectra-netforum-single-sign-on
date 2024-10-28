<?php

namespace WP\Views;

use Netforum\Traits\SingletonTrait;

abstract class View extends Form {

    use SingletonTrait;

    protected $group;
    protected $fields = array('abstract' => array('desc' => 'this is the abstract section.','fields' => array('field1' => array('title' => 'abstract field 1','desc' => 'you should extend this abstract class and not use it directly.',
                    'validate' => array('[a-zA-Z0-9_]{5,}','must be minimum 5 characters, format (a-z 0-9 _-)'),'required' => true,'callback' => null,'default' => 'some value','filter' => 'trim'))));

    public function __construct() {
        $this->page = Page::getCurrentPage();
        $this->group = snake_case(end(explode('\\', get_called_class())));
        if (isset($_POST[$this->page]) && is_countable($_POST[$this->page]) && count($_POST[$this->page]) > 0) {
            //print_r($_POST[$this->page]);
            !$this->validate() ? $this->flash(true) : $this->store() && $this->flash();
        } 
        $this->init();
        $this->render();
    }

    protected function init() {
        register_setting($this->page, $this->group, array($this,'sanitize'));
        if (is_countable($this->fields) && count($this->fields) <= 0) {
            return false;
        } array_walk($this->fields,
        
        function ($e,$key)
            {
            $this->makeSection($key, $e['desc']);

            if (isset($e['js'])) {
                Input::handleCallback($e['js']);
            } 
            array_walk($e['fields'], function ($spc42dff,$k) use($key)
                {
                $spc42dff += array('key' => $k,'section' => $key);
               $this->makeField($k, $spc42dff);

            });
        });
    }

    protected function render() {
        if (is_countable($this->fields) && count($this->fields) > 0) {
            settings_fields($this->group);
            do_settings_sections($this->page);
            submit_button();
        }
    }

    protected function makeSection($title, $desc = null, $cb = null) {
        if (is_null($cb)) {
            $cb = function () use($desc)
                {
                print $desc;
            };
        } return add_settings_section($this->toSlug($title), ucwords($title), $cb, $this->page);
    }

    protected function makeField($id, $e) {
        if (is_null($e['callback'])) {
            $e['callback'] = 'textfield';
        } 
        //echo '<pre>';
        if (!is_array($e['callback'])) {
            $e['callback'] = array(__NAMESPACE__ . '\\Input',$e['callback']);
        } 

        $section = $this->toSlug($e['section']);
        $e['args'] = array();
        if (is_countable($e['args']) && count($e['args']) <= 0) {
            $e['args'] = array('group' => $this->group,'section' => $section,'id' => $id,'desc' => $e['desc'],'default' => $e['default'],'filter' => $e['filter'],'js' => $e['js']);
        } 

        return add_settings_field($id, $e['title'], $e['callback'], $this->page, $section, $e['args']);
    }

    protected function toSlug($e, $delimiter = '_') {
        return preg_replace('/[^\\w]/', $delimiter, strtolower($e));
    }

}
