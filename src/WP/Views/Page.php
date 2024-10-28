<?php

namespace WP\Views;

use WP\Traits\Helpers;

class Page {

    use Helpers;

    public static $default;
    public static $static;

    public function __construct() {
        self::set('caller', get_called_class());
        $actions = array('nf_logo' => 'getHeaderLogo','nf_head' => 'getHeader','nf_tabs' => 'getTabs','nf_foot' => 'getFooter');
        add_action('admin_menu', array($this,'makeView'));
        array_walk($actions, function ($v,$k)
            {
                if (method_exists(self::get('caller'), $v)) 
                {
                    add_action($k, array(self::get('caller'),$v));
                }
            });
    }

    public static function getTpl() {
        $obj = self::get('namespace') . '\\' . ucfirst(camel_case(self::get('page')));
        class_exists($obj) ? add_action('nf_body', array($obj,'getInstance')) : null;
        
        include_once self::get('templatePath') . '/main.tpl';

        if (!class_exists($obj)) {
            dd('class ' . $obj . ' doesn\'t exist.');
        }
    }

    public static function makeView() {
        self::bootstrap();
        $caller = self::get('caller');
        array_walk($caller::$plugin,

           
            function ($v,$k) use($caller)
            {
                 
            if (!self::isMenuItemExists($k)) {
                add_menu_page(ucwords($k), $k, 'manage_options', $v['uri'], array($caller,'getTpl'), self::get('assetsUrl') . $v['menu_logo']);
            } array_walk($v['pages'],
                    function ($js,$page) use($v,$caller)
                {
                add_submenu_page($v['uri'], ucwords($page), ucwords($page), 'manage_options', $js, array($caller,'getTpl'));
            });
        });
    
    }

    public static function isMenuItemExists($item) {
        global $menu;
        $exists = false;
        array_walk($menu, function ($v) use($item,&$exists)
            {
            if (preg_match('/^' . trim($item) . '$/i', $v[0])) {
                $exists = true;
            }
            });
        return $exists;
    }

    protected static function makeTab($tab, $page, $desc = null) {
        $active = $page == self::getCurrentPage() ? 'nav-tab-active' : '';
        printf('<a class=\'nav-tab %s\' href=\'?page=%s\' title=\'%s\'>%s</a>', $active, is_null($page) ? self::getCurrentPage() : $page, ucfirst($desc), ucwords($tab));
    }

    public static function getTabs() {
        $caller = self::get('caller');
        array_walk(array_shift($caller::$plugin)['pages'], function ($js,$page)
            {
            self::makeTab($page, $js);
        });
    }

}
