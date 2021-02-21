<?php
namespace  App;
if( ! defined( 'XBOX_HIDE_DEMO' ) ){
    define( 'XBOX_HIDE_DEMO', true );
}
class AdminBox{

    public function __construct()
    {
        add_action('init', [$this, 'require_xbox']);
        add_action( 'xbox_init', [$this,'admin_page_domimerk']);
    }

    public function require_xbox(){
        require_once( './xbox/xbox.php');
    }

    public function admin_page_domimerk(){
        $options = array(
            'id' => 'form_cotiza_domimmerk',//It will be used as "option_name" key to save the data in the wp_options table
            'title' => 'Sistema de envios',
            'menu_title' => 'Config Envios Domimerk',
            'form_options' => array(
                'id' => 'id-form-tag',
                'action' => '',
                'method' => 'post',
                'save_button_text' => __('Guardar Cambios', 'formdomimerk'),
                'save_button_class' => '',
                'reset_button_text' => __('Resetear', 'formdomimerk'),
                'reset_button_class' => '',
            ),
        );

        $xbox = xbox_new_admin_page( $options );

        $group = $xbox->add_group( array(
            'name' => 'Municipio',
            'id' => 'municipality',
            'options' => array(
                'add_item_text' => __('Agregar Municipio', 'formdomimerk'),
                'remove_item_text' => '',
                'sortable' => true,
            ),
            'controls' => array(
                'name' => 'Municipio #',
                'readonly_name' => false
            )
        ));
        $group->add_field(array(
            'id' => 'name_municipality',
            'name' => __( 'Nombre Municipio', 'formdomimerk' ),
            'type' => 'text',
        ));
        $group->add_field(array(
            'id' => 'pressing_min_send',
            'name' => __( 'Precio Minimo de Envio', 'formdomimerk' ),
            'type' => 'text',
        ));
        $group->add_field(array(
            'id' => 'pressing_max_send',
            'name' => __( 'Precio Máximo x KM', 'formdomimerk' ),
            'type' => 'text',
        ));
        $group->add_field(array(
            'id' => 'size_cover',
            'name' => __( 'Cobertura', 'formdomimerk' ),
            'type' => 'text',
            'default'=>'20',
        ));

        $xbox->add_field(array(
            'id' => 'image_logo_delivery',
            'name' => __( 'Imagen Logo', 'formdomimerk' ),
            'type' => 'file',
        ));
        $xbox->add_field(array(
            'id' => 'email_form_delivery',
            'name' => __( 'Email', 'formdomimerk' ),
            'type' => 'text',
            'default' => '',
        ));
        $xbox->add_field(array(
            'id' => 'subject_form_delivery_admin',
            'name' => __( 'Asunto Administrador', 'formdomimerk' ),
            'type' => 'text',
            'default' => '',
        ));
        $xbox->add_field(array(
            'id' => 'subject_form_delivery_client',
            'name' => __( 'Asunto Cliente', 'formdomimerk' ),
            'type' => 'text',
            'default' => '',
        ));
       $xbox->add_field(array(
                    'id' => 'form_number_mobil',
                    'name' => __( 'Número Celular', 'formdomimerk' ),
                    'type' => 'text',
                    'default' => '',
        ));
        $xbox->add_field(array(
            'id' => 'api_key_map_delivery',
            'name' => __( 'API kEY', 'formdomimerk' ),
            'type' => 'text',
            'default' => '',
        ));

    }
}

