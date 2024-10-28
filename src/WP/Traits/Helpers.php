<?php

namespace WP\Traits;

trait Helpers {

    public static function set($option, $value, $for = null) {
        $class = is_null($for) ? get_called_class() : $for;
        self::$static[$class][$option] = $value;
    }

    public static function get($key, $for = null) {
        $class = is_null($for) ? get_called_class() : $for;
        return isset(self::$static[$class][$key]) ? self::$static[$class][$key] : null;
    }

    public static function whoami() {
        return get_called_class();
    }

    public static function bootstrap() {
        $class = get_called_class();
        $object = new \ReflectionObject(new $class());
        $dir = dirname($object->getFileName());
        $dir = dirname(dirname($dir));
        self::set('pluginPath', plugin_dir_path($dir));
        self::set('pluginUrl', plugin_dir_url($dir));
        self::set('page', self::getCurrentPage());
        self::set('caller', get_called_class());
        self::set('namespace', self::getNamespace(get_called_class()));
        self::set('assetsUrl', self::get('pluginUrl') . 'assets');
        self::set('templatePath', self::get('pluginPath') . 'assets/templates');
        self::set('jsPath', self::get('pluginPath') . 'assets/javascripts');
    }

    public static function getCurrentPage() {
        return isset($_GET['page']) ? sanitize_title($_GET['page']) : self::getDefaultPage();
    }

    public static function getJsPath($pluginPath = null) {
        $path = is_null($pluginPath) ? self::get('pluginPath') : dirname($pluginPath);
        $js_e = self::get('jsPath');
        return empty($js_e) ? $path . '/assets/javascripts' : $js_e;
    }

    public static function getAssetsUrl($pluginPath = null) {
        $path = is_null($pluginPath) ? self::get('pluginUrl') : plugin_dir_url($pluginPath);
        $js = self::get('assetsUrl');
        return empty($js) ? esc_url_raw($path . 'assets') : esc_url_raw($js);
    }

    public static function getTemplatesPath($pluginPath = null) {
        $path = is_null($pluginPath) ? self::get('pluginPath') : dirname(dirname(dirname($pluginPath)));
        $tpl = self::get('templatePath');
        return empty($tpl) ? $path . '/assets/templates' : $tpl;
    }

    public static function getDefaultPage() {
        return self::$default;
    }

    public static function getPluginInfo() {
        return get_plugin_data(self::get('pluginPath') . '/' . self::getPluginFileName());
    }

    public static function getPluginVersion() {
        return self::getPluginInfo()['Version'];
    }

    public static function getPluginFileName($ext = true) {
        return plugin_basename(self::get('pluginPath')) . ($ext ? '.php' : '');
    }

    public static function getNamespace($c) {
        return '\\' . substr($c, 0, strrpos($c, '\\'));
    }

}
