<?php

namespace NetAuth;

class Authenticate {

    private $ssoToken;

    public function __construct() {
        add_filter('authenticate', array($this,'validate'), 10, 3);
    }

    public function validate($user, $username, $password) {
        
        if (empty($username) || empty($password)) {
            return false;
        } 
        try {
            $user = get_user_by('login', $username);
            
            
            if (is_object($user) && !preg_match('/@/', $username)) {
                return false;
            } 
            $nf = (array) $this->authenticate($username, $password);
           // print_r($nf);exit;
            
            $uData = array('user_email' => strtolower($nf->EmailAddress),'user_login' => strtolower($nf->EmailAddress),'first_name' => ucwords($nf->ind_first_name),'last_name' => ucwords($nf->ind_last_name),
                'nickname' => ucwords($nf->ind_first_name) . ' ' . ucwords($nf->ind_last_name),'');

            if (!is_object($user)) {
                if ($syncId = $this->isSyncNeeded($nf->cst_id, $user->ID)) {
                    global $wpdb;
                    $wpdb->update($wpdb->users, array_slice($uData, 0, 2), array('ID' => $syncId));
                    $user = new \WP_User(wp_update_user(array('ID' => $syncId) + $uData));
                } else {
                    $create = wp_insert_user($uData);
                    if ($create instanceof \WP_Error) {
                        if (array_key_exists('existing_user_email', $create->errors)) {
                            $create = get_user_by('email', $uData['user_email']);
                        }
                    } $user = new \WP_User($create);
                }
            } else {
                wp_update_user($uData + array('ID' => $user->ID));
            } $this->setSession($nf, $user);
            do_action('nf_SyncGroups');
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            if (preg_match('/(Unable\\sto\\slogin|Invalid\\sCredentials)/i', $msg)) {
                $msg = 'The credentials you entered does not match our records.';
            } remove_action('authenticate', 'wp_authenticate_username_password', 20);
            $user = new \WP_Error('denied', __('Uh Oh!<br> ' . $msg));
        } return $user;
    }

    protected function setSession($nf, $user) {
        $params = array('cst_id' => (int) $nf->cst_id,'cst_key' => (string) $nf->cst_key,'sso_token' => $this->ssoToken);
        update_user_meta($user->ID, 'netforum', $params);
        if (!session_id()) {
            session_start();
        } $_SESSION += array('netforum' => $params);
        $getDomain = function ($url)
            {
            if (!preg_match('/^http/', $url)) {
                $url = 'http://' . $url;
            } if ($url[strlen($url) - 1] != '/') {
                $url .= '/';
            } $pieces = parse_url($url);
            $domain = isset($pieces['host']) ? $pieces['host'] : '';
            if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\\-]{1,63}\\.[a-z\\.]{2,6})$/i', $domain, $regs)) {
                $res = preg_replace('/^www\\./', '', $regs['domain']);
                return $res;
            } return false;
        };
        @setcookie('ssoToken', $params['sso_token'], time() + 86400, '/', $getDomain($_SERVER['HTTP_HOST']), false);
    }

    protected function authenticate($username, $password) {
        $options = get_option('netforum');
        if (!is_array($options) || empty($options['single_sign_on']['wsdl'])) {
            throw new \Exception('Something went wrong, netforum xweb credentials not set.');
        } $wp_options = array('debug' => false,'ttl' => 12,'timeout' => $options['connection']['timeout'],'wsdl' => $options['single_sign_on']['wsdl'],'username' => $options['single_sign_on']['username'],
            'password' => $options['single_sign_on']['password'],'credentials' => array('username' => $username,'password' => $password));
        if (class_exists('Netforum\\Views\\NetforumCache')) {
            $options = get_option('netforum_cache');
            $wp_options += array('cache' => array('path' => __DIR__ . '/tmp/','secret' => $options['cache']['key'],'ttl' => $options['cache']['ttl']));
        } $nf = new \Netforum\Providers\ServiceProvider($wp_options);
        if (property_exists($nf, 'auth')) {
            $this->ssoToken = $nf->auth->getSsoToken();
            return $nf->onDemand->getCustomerByKey();
        } else {
            $this->ssoToken = $nf->simple->getSsoToken();
            return $nf->simple->getCustomerByKey();
        }
    }

    private function isSyncNeeded($cstId, $userId) {
        global $wpdb;
        if ($cstId <= 0) {
            return false;
        } $q = $wpdb->get_row(sprintf('select * from %s where meta_value like "%s" limit 1', $wpdb->usermeta, '%\\"cst_id\\";i:' . (int) esc_sql($cstId) . ';%'));
        if (!is_object($q)) {
            return false;
        } return $userId != $q->user_id ? $q->user_id : false;
    }

}