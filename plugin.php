<?php
/*
Plugin Name: Form Delivery Espress Domimerk
Plugin URI: https://example.com/
Description: This from delivery domimmerk of espress, calculate km and total value cash delivery. shortcode [form-domimerk-espress-delivery]
Version: 0.1
Author: David Fernando Valenzuela Pardo
Author URI: https://github.com/valenzuela21
License: GPL2
License URI: https://creativo.cloudberry.com.co/
Text Domain: formdomimerk
Domain Path: /languages
*/

/**
 * Copyright (c) YEAR Your Name (email: Email). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

// don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Base_Plugin class
 *
 * @class Base_Plugin The class that holds the entire Base_Plugin plugin
 */
final class Form_Domimerk {

    /**
     * Plugin version
     *
     * @var string
     */
    public $version = '0.1.0';

    /**
     * Holds various class instances
     *
     * @var array
     */
    private $container = array();

    /**
     * Constructor for the Base_Plugin class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     */
    public function __construct() {

        $this->define_constants();

        register_activation_hook( __FILE__, array( $this, 'activate' ) );
        register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

        add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
    }

    /**
     * Initializes the Base_Plugin() class
     *
     * Checks for an existing Base_Plugin() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new Form_Domimerk();
        }

        return $instance;
    }

    /**
     * Magic getter to bypass referencing plugin.
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __get( $prop ) {
        if ( array_key_exists( $prop, $this->container ) ) {
            return $this->container[ $prop ];
        }

        return $this->{$prop};
    }

    /**
     * Magic isset to bypass referencing plugin.
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __isset( $prop ) {
        return isset( $this->{$prop} ) || isset( $this->container[ $prop ] );
    }

    /**
     * Define the constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'FORMDOMIMERK_VERSION', $this->version );
        define( 'FORMDOMIMERK_FILE', __FILE__ );
        define( 'FORMDOMIMERK_PATH', dirname( FORMDOMIMERK_FILE ) );
        define( 'FORMDOMIMERK_INCLUDES', FORMDOMIMERK_PATH . '/includes' );
        define( 'FORMDOMIMERK_URL', plugins_url( '', FORMDOMIMERK_FILE ) );
        define( 'FORMDOMIMERK_ASSETS', FORMDOMIMERK_URL . '/assets' );
    }

    /**
     * Load the plugin after all plugis are loaded
     *
     * @return void
     */
    public function init_plugin() {
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Placeholder for activation function
     *
     * Nothing being called here yet.
     */
    public function activate() {

        $installed = get_option( 'formdomimerk_installed' );

        if ( ! $installed ) {
            update_option( 'formdomimerk_installed', time() );
        }

        update_option( 'formdomimerk_version', FORMDOMIMERK_VERSION );
    }

    /**
     * Placeholder for deactivation function
     *
     * Nothing being called here yet.
     */
    public function deactivate() {

    }

    /**
     * Include the required files
     *
     * @return void
     */
    public function includes() {

        require_once FORMDOMIMERK_INCLUDES . '/Assets.php';

        if ( $this->is_request( 'admin' ) ) {
            require_once FORMDOMIMERK_INCLUDES . '/AdminBox.php';
        }

        if ( $this->is_request( 'frontend' ) ) {
            require_once FORMDOMIMERK_INCLUDES . '/Frontend.php';
        }

    }

    /**
     * Initialize the hooks
     *
     * @return void
     */
    public function init_hooks() {

        add_action( 'init', array( $this, 'init_classes' ) );

        // Localize our plugin
        add_action( 'init', array( $this, 'localization_setup' ) );
    }

    /**
     * Instantiate the required classes
     *
     * @return void
     */
    public function init_classes() {
        if ( $this->is_request( 'admin' ) ) {
            $this->container['admin'] = new App\AdminBox();
        }

        if ( $this->is_request( 'frontend' ) ) {
            $this->container['frontend'] = new App\FrontendXpress();
        }

        $this->container['assets'] = new App\AssetsDomimerk();
    }

    /**
     * Initialize plugin for localization
     *
     * @uses load_plugin_textdomain()
     */
    public function localization_setup() {
        load_plugin_textdomain( 'formdomimerk', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * What type of request is this?
     *
     * @param  string $type admin, ajax, cron or frontend.
     *
     * @return bool
     */
    private function is_request( $type ) {
        switch ( $type ) {
            case 'admin' :
                return is_admin();

            case 'ajax' :
                return defined( 'DOING_AJAX' );

            case 'rest' :
                return defined( 'REST_REQUEST' );

            case 'cron' :
                return defined( 'DOING_CRON' );

            case 'frontend' :
                return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
        }
    }

} 

$baseplugin = Form_Domimerk::init();
