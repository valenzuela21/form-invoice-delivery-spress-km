<?php
namespace App;

/**
 * Scripts and Styles Class
 */
class AssetsDomimerk {

    function __construct() {

        if ( is_admin() ) {
            add_action( 'admin_enqueue_scripts', [ $this, 'register' ], 5 );
        } else {
            add_action( 'wp_enqueue_scripts', [ $this, 'register' ], 5 );
            add_action('wp_enqueue_scripts', [$this,'style_script_css_register'], 5);
        }
    }

    /**
     * Register our app scripts and styles
     *
     * @return void
     */
    public function register() {
        $this->register_scripts( $this->get_scripts() );
        $this->register_styles( $this->get_styles() );
    }


    public function style_script_css_register(){
        // Register the script
        wp_enqueue_script('script_form_delivery', plugins_url('../../src/frontend/components/parts/params.js', __FILE__), array(), 1.0, true );
        // Opcions Admin
        $xbox = get_option( 'form_cotiza_domimmerk' );
        $municipality = $xbox['municipality'];
        $logo_form_delivery = $xbox['image_logo_delivery'];
        $api_key = $xbox['api_key_map_delivery'];
        $mobil = $xbox['form_number_mobil'];
        // Localize the script with new data
        $translation_array = array(
            'url' => plugins_url('./sendMail/mailing.php', __FILE__),
            'municipaly' => $municipality,
            'logo' => $logo_form_delivery,
            'apikey'=> $api_key,
            'mobil'=>$mobil
        );
        wp_localize_script( 'script_form_delivery', 'object_delivery', $translation_array );

        // Enqueued script with localized data.
        wp_enqueue_script( 'script_form_delivery' );
    }

    /**
     * Register scripts
     *
     * @param  array $scripts
     *
     * @return void
     */
    private function register_scripts( $scripts ) {
        foreach ( $scripts as $handle => $script ) {
            $deps      = isset( $script['deps'] ) ? $script['deps'] : false;
            $in_footer = isset( $script['in_footer'] ) ? $script['in_footer'] : false;
            $version   = isset( $script['version'] ) ? $script['version'] : FORMDOMIMERK_VERSION;

            wp_register_script( $handle, $script['src'], $deps, $version, $in_footer );

        }
    }

    /**
     * Register styles
     *
     * @param  array $styles
     *
     * @return void
     */
    public function register_styles( $styles ) {
        foreach ( $styles as $handle => $style ) {
            $deps = isset( $style['deps'] ) ? $style['deps'] : false;

            wp_register_style( $handle, $style['src'], $deps, FORMDOMIMERK_VERSION );
        }
    }

    /**
     * Get all registered scripts
     *
     * @return array
     */
    public function get_scripts() {
        $prefix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.min' : '';

        $scripts = [
            'formdomimerk-runtime' => [
                'src'       => FORMDOMIMERK_ASSETS . '/js/runtime.js',
                'version'   => filemtime( FORMDOMIMERK_PATH . '/assets/js/runtime.js' ),
                'in_footer' => true
            ],
            'formdomimerk-vendor' => [
                'src'       => FORMDOMIMERK_ASSETS . '/js/vendors.js',
                'version'   => filemtime( FORMDOMIMERK_PATH . '/assets/js/vendors.js' ),
                'in_footer' => true
            ],
            'formdomimerk-frontend' => [
                'src'       => FORMDOMIMERK_ASSETS . '/js/frontend.js',
                'deps'      => [ 'jquery', 'formdomimerk-vendor', 'formdomimerk-runtime' ],
                'version'   => filemtime( FORMDOMIMERK_PATH . '/assets/js/frontend.js' ),
                'in_footer' => true
            ],


        ];

        return $scripts;
    }

    /**
     * Get registered styles
     *
     * @return array
     */
    public function get_styles() {

        $styles = [
            'formdomimerk-style' => [
                'src' =>  FORMDOMIMERK_ASSETS . '/css/style.css'
            ],
            'formdomimerk-frontend' => [
                'src' =>  FORMDOMIMERK_ASSETS . '/css/frontend.css'
            ],
            'formdomimerk-frontend_buefy' => [
                'src' =>  FORMDOMIMERK_ASSETS . '/css/buefy.min.css'
            ],
        ];

        return $styles;
    }

}
