<?php

namespace NetAuth\Views;

use WP\Views\View;

class Netforum extends View {

    protected $fields = array('single sign on' => array('desc' => 'xWeb API settings for netFORUM.','fields' => array('wsdl' => array('title' => 'xWeb WSDL Url','desc' => 'xWeb WSDL Url','validate' => array(
                        '(https?:\\/\\/)?([\\da-z\\.-]+)\\.([a-z\\.]{2,6})([\\/\\w \\.-]*)*\\/?.+','must start with http:// or https://'),'required' => true),'username' => array('title' => 'xWeb Username',
                    'desc' => 'Username to the xWeb user account, format (a-z 0-9 _-)','validate' => array('[a-zA-Z0-9_]+','format (a-z 0-9 _-)'),'required' => true),'password' => array('title' => 'xWeb Password',
                    'desc' => 'Password to the xWeb user account.','required' => true,'callback' => 'passwordfield'))),'connection' => array('desc' => 'Connection timeout settings for netFORUM.','fields' => array(
                'timeout' => array('title' => 'Timeout','desc' => 'How long to wait to hear a reply from netFORUM.','validate' => array('\\d{1,2}','must be numeric.'),'required' => true,'default' => 9),
                'connect_timeout' => array('title' => 'Connection Timeout','desc' => 'How long to wait for the initial connection.','validate' => array('\\d{1,2}','must be numeric.'),'required' => true,
                    'default' => 9))));

}
