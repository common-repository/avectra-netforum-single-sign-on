<?php

namespace NetAuth\Views;

use WP\Views\View;

class NetforumCache extends View {

    protected $fields = array('cache' => array('desc' => 'Cache configuration for netFORUM module. <p><strong>Warning: </strong>Cache module has been disabled in this basic version. <br>Please contact <strong>help@fusionspan.com</strong> for full version.</p>',
            'fields' => array('key' => array('title' => 'Cache Secret Key','desc' => 'Enter exactly 16 or 20 random characters for cache encryption.','validate' => array('.{16,20}','must be exactly 16 or 20 random characters.'),
                    'required' => true,'default' => array('wp_generate_password',array(20,true))),'ttl' => array('title' => 'Cache TTL','desc' => 'Enter cache time to live settings in seconds.','validate' => array(
                        '\\d+','must be numeric.'),'required' => true,'default' => 86400))));

}
