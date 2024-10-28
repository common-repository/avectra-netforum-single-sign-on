<?php

/**
 * Plugin Name: fusionSpan | netFORUM Single Sign On
 * Plugin URI: http://fusionspan.com/
 * Description: Authenticate users to sign in using Avectra netFORUM credentials via xWeb.
 * Version: 1.3.5
 * Author: fusionSpan LLC.
 * Author URI: http://fusionspan.com/
 * License: GPLv3
 */
if ( ! function_exists( 'ansso_fs' ) ) {
    // Create a helper function for easy SDK access.
    function ansso_fs() {
        global $ansso_fs;

        if ( ! isset( $ansso_fs ) ) {
            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';

            $ansso_fs = fs_dynamic_init( array(
                'id'                  => '5151',
                'slug'                => 'avectra-netforum-single-sign-on',
                'type'                => 'plugin',
                'public_key'          => 'pk_75a2f1fbb3f19b48c71c256148222',
                'is_premium'          => true,
                'is_premium_only'     => true,
                'has_addons'          => false,
                'has_paid_plans'      => true,
                'menu'                => array(
                    'slug'           => 'avectra-netforum-single-sign-on',
                    'first-path'     => 'admin.php?page=netforum',
                    'contact'        => false,
                    'support'        => false,
                ),
                // Set the SDK to work in a sandbox mode (for development & testing).
                // IMPORTANT: MAKE SURE TO REMOVE SECRET KEY BEFORE DEPLOYMENT.
                'secret_key'          => 'sk_dK3%&TtZ0b72ev7N=j=KEmNg%Z@))',
            ) );
        }

        return $ansso_fs;
    }

    // Init Freemius.
    ansso_fs();
    // Signal that SDK was initiated.
    do_action( 'ansso_fs_loaded' );
}
require 'src/helpers.php';
registerAutoloader();
if (!defined('WPINC')) {
    die;
} 

if (is_admin()) {
    new \NetAuth\Views\Render();
} new \NetAuth\Authenticate();
new \NetAuth\RestrictPassword();
new \NetAuth\RemoveAdminBar();