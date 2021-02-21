<?php
namespace App;

/**
 * Frontend Pages Handler
 */
class FrontendXpress {

    public function __construct() {
        add_shortcode( 'form-domimerk-espress-delivery', [ $this, 'render_frontend' ] );
    }

    /**
     * Render frontend app
     *
     * @param  array $atts
     * @param  string $content
     *
     * @return string
     */
    public function render_frontend( $atts, $content = '' ) {
        wp_enqueue_style( 'formdomimerk-frontend_buefy' );
        wp_enqueue_style( 'formdomimerk-frontend' );
        wp_enqueue_script( 'formdomimerk-frontend' );

        $content .= '<div id="vue-frontend-app"></div>';

        return $content;
    }
}
