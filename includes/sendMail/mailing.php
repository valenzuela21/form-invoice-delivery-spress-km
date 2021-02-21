<?php
include(dirname(__FILE__) . "/load.php");

$data = json_decode(file_get_contents("php://input"), true);
define("HTML_EMAIL_HEADERS", array('Content-Type: text/html; charset=UTF-8'));

function wpb_sender_name($original_email_from)
{
    return get_bloginfo('name');
}

add_filter('wp_mail_from_name', "wpb_sender_name");

// @email - Email address of the reciever
// @subject - Subject of the email
// @heading - Heading to place inside of the woocommerce template
// @message - Body content (can be HTML)
function send_email_woocommerce_style($email, $subject, $heading, $message) {

    // Get woocommerce mailer from instance
    $mailer = WC()->mailer();

    // Wrap message using woocommerce html email template
    $wrapped_message = $mailer->wrap_message($heading, $message);

    // Create new WC_Email instance
    $wc_email = new WC_Email;

    // Style the wrapped message with woocommerce inline styles
    $html_message = $wc_email->style_inline($wrapped_message);

    // Send the email using wordpress mail function
    wp_mail( $email, $subject, $html_message, HTML_EMAIL_HEADERS );

}

$xbox = get_option( 'form_cotiza_domimmerk' );
$municipality = $xbox['municipality'];
$email_admin = $xbox['email_form_delivery'];
$subject_admin = $xbox['subject_form_delivery_admin'];
$subject_client = $xbox['subject_form_delivery_client'];

$heading_admin = $subject_admin;

$total = number_format($data['total'], 0, '', '.');

$message_admin = '<div>
        <h4>Hay una nueva solicitud  de servicio de paqueteria</h4>
        <p><strong>Nombre completo: </strong>'.$data['name'].'</p>
        <p><strong>Número telofónico: </strong>'.$data['phone'].'</p>
        <p><strong>Correo electrónico: </strong>'.$data['email'].'</p>
        <h3>Información Origen</h3>
        <p><strong>Municipio: </strong>'.$data['city_source']['city'].'</p>
        <p><strong>Dirección: </strong>'.$data['address_source'].'</p>
        <h3>Información Destino</h3>
        <p><strong>Municipio: </strong>'.$data['city_destiny']['city'].'</p>
        <p><strong>Dirección: </strong>'.$data['address_destiny'].'</p>
        <h4>Distancia: '.$data['distance'].' Km </h4>
        <h4>Total: $'.$total.'</h4>
        
</div>';


$heading_client = $subject_client;

$message_client = '<div>
        <h2>Tu Nuevo Pedido DomimerkSpress</h2>
        <p>Este el resultado de nuevo despacho muy pronto estarás con un asesor validando los datos.</p>
        <p>Distancia: '.$data['distance'].' Km</p>
        <p>Total: $'.$total.'</p> 
        <p>Mayor información: https://domimerkespress.com/</p> 
		<p>(+57) 300 825 7741 (035) 395 99 06
(+57) 300 242 9192 </p>
</div>';

send_email_woocommerce_style($email_admin, $subject_admin, $heading_admin, $message_admin);
send_email_woocommerce_style($data['email'], $subject_client, $heading_client, $message_client);