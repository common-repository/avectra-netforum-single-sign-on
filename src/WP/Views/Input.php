<?php

namespace WP\Views;

class Input {

    public static function textfield($args) {
        return self::field($args, $type = 'text');
    }

    public static function passwordfield($args) {
        return self::field($args, $type = 'password');
    }

    public static function checkbox($args) {
        return self::field($args, $type = 'checkbox', null);
    }

    public static function textarea($args) {
        return self::field($args, $type = 'textarea', null);
    }

    protected static function field($args, $type = 'text', $class = 'regular-text') {
        $id = $args['id'];
        $group = $args['group'];
        $section = $args['section'];
        $fieldName = "{$group}[{$section}][{$id}]";
        $value = self::makeDefaultValue($args);
        $extra = '';
        if ($type == 'checkbox') {
            $extra .= checked(1, (bool) $value, false);
            if (!checked(1, (bool) $value, false)) {
                $value = checked(1, $value, false) ? 0 : 1;
            }
        } if (!is_null($args['js'])) {
            self::addJS($args['js'], $fieldName, $value, $args);
        } $params = array('class' => $class,'type' => $type,'name' => $fieldName,'value' => esc_attr($value),'extra' => $extra);
        switch ($type) {
            case 'textarea': self::itemTextArea($params);
                break;
            default: self::itemTextField($params);
        } self::addDescription($args['desc']);
    }

    private final static function itemTextArea(array $params) {
        printf('<textarea cols=30 rows=4 class=\'%s\' type=\'%s\' name=\'%s\' %s>%s</textarea>', $params['class'], $params['type'], $params['name'], $params['extra'], $params['value']);
    }

    private static function itemTextField(array $params) {
        printf('<input class=\'%s\' type=\'%s\' name=\'%s\' value=\'%s\' %s />', $params['class'], $params['type'], $params['name'], $params['value'], $params['extra']);
    }

    private static function addDescription($desc) {
        if (is_array($desc)) {
            printf('<small>&nbsp; %s</small><br><small>%s</small>', array_shift($desc), esc_attr(array_pop($desc)));
        } else {
            printf('<br><small>%s</small>', esc_attr($desc));
        }
    }

    private static function makeDefaultValue(array $args) {
        $default = $args['default'];
        $filter = $args['filter'];
        $return = self::getOptionValue($args);
        if (is_null($return) && is_array($default)) {
            $default = self::handleCallback($default);
        } if (is_array($filter)) {
            $return = self::handleCallback($filter, array(is_null($return) ? $default : $return));
        } return is_null($return) ? $default : $return;
    }

    public static function handleCallback($callback, array $return = array()) {
        if (!is_array($callback)) {
            return $return;
        } $call = array_shift($callback);
        $params = is_array(end($callback)) ? is_null($return) ? end($callback) : array_merge(end($callback), $return) : $return;
        return call_user_func_array($call, $params);
    }

    private static function getOptionValue(array $args) {
        $id = $args['id'];
        $group = $args['group'];
        $section = $args['section'];
        if (isset($_REQUEST[$group][$section][$id])) {
            return $_REQUEST[$group][$section][$id];
        } 

        $options = get_option($group);
        //print_r($options);

        if(isset($options[$id]) && !empty($options[$id]))
        {
            $options[$id] = $options[$id];
        }elseif(isset($options[$section][$id]) && !empty($options[$section][$id])){
            $options[$id] = $options[$section][$id];
        }else{
            $options[$id] = null;
        }
        return $options[$id];
    }

    public static function addJS($js_e, $key = '', $value = '', $args = '') {
        $group = $section = null;
        is_array($args) ? extract($args) : null;
        $getInput = function ($key,$delim = '')
            {
            return sprintf('$(\'input[name%s="%s"]\')', $delim, $key);
        };
        printf('<script>jQuery(document).ready(function($) { %s });</script>',
                preg_replace(array('/%field:(.*?)%/i','/%field/','/%value/'), array($getInput('[$1]', '*'),$getInput($key),esc_attr($value)), self::compress($js_e)));
    }

    protected static function compress($buffer) {
        $buffer = preg_replace('/((?:\\/\\*(?:[^*]|(?:\\*+[^*\\/]))*\\*+\\/)|(?:\\/\\/.*))/', '', $buffer);
        $buffer = str_replace(array('
','
','	','
','  ','    ','     '), '', $buffer);
        $buffer = preg_replace(array('(( )+\\))','(\\)( )+)'), ')', $buffer);
        return $buffer;
    }

}