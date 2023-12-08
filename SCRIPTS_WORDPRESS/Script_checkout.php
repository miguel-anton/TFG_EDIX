
<?php
/*
*
* ESTE SCRIPT REALMENTE SE ENCUENTRA EN EL ARCHIVO FUNCTIONS.PHP DEL TEMA QUE USO EN EL WORDPRESS
*
*/
 
function postcode_shortcode($atts, $content = null) {
    // OBLIGAR AL USUARIO A INTRODUCIR EL CODIGO POSTAL SI SE TRATA DE UN ENVIO A
    if( isset($_POST['shipping_type']) && 'delivery' === $_POST['shipping_type'] && empty($_POST['postcode']) ) {
        $error_message = '<div class="woocommerce"><ul class="woocommerce-error" role="alert"><ul>
            <li>Es obligatorio rellenar el codigo postal para  "ENVIO A DOMICILIO"</li>
        </ul></div>';
    } else {
        $error_message = '';
    }

    // ESTRUCTURA DEL FORMULARIO
    $html = <<<HTML
        <form method="post" class="formulario-tipo-envio">
            <label> Si quieres "ENVIO A DOMICILIO" introduce el CODIGO POSTAL<br>
            <input id="input-postcode" name="postcode" type="text" />
			<div class="botones-envio">
            <button name="shipping_type" value="delivery" type="submit">ENVIO A DOMICILIO</button>
            <button name="shipping_type" value="pickup" type="submit">RECOGER EN LOCAL</button>
			</div>
        </form>
HTML;

    // DEVUELVE EL ERROR
    return $error_message . $html;
}

add_shortcode('envioForm_shortcode', 'postcode_shortcode');



// FUNCION QUE RECOGE LOS DISTINTOS TIPOS DE ENVIO
function get_shipping_rates_id_from_chosen( $chosen_shipping ) { 
    // para recoger en local
    if ( 'pickup' === $chosen_shipping ) {
        return 'local_pickup:3'; 
    } 
    // para envio
    elseif ( 'delivery' === $chosen_shipping ) {
        return 'flat_rate:1'; 
    }
	//para comer en local
	elseif ( 'localEating' === $chosen_shipping ) {
        return 'local_pickup:4'; 
    }
}
    
add_action('init', 'set_chosen_shipping_type_to_session');
function set_chosen_shipping_type_to_session() {
    if ( isset($_POST['shipping_type']) ) {
        // ABILITAR LA SESION DEL USUARIO
        if ( isset(WC()->session) && ! WC()->session->has_session() ) {
            WC()->session->set_customer_session_cookie( true );
        }
        
        // CONSIGUE EL TIPO DE ENVIO
        if ( isset($_POST['shipping_type']) ) {
            // Set chosen shipping type in a session variable
            WC()->session->set('chosen_shipping', wc_clean($_POST['shipping_type']));
        }
       
    }
}

// REDIRECCION A LA PAGINA DE PEDIDO CON EL TIPO DE ENVIO ELEGIDO
add_action('template_redirect', 'action_template_redirect');



function action_template_redirect($shipping) {
   
    // redireccion
    if ( isset($_POST['shipping_type']) && WC()->session->get('chosen_shipping') ) {
        wp_redirect(home_url('/haz-tu-pedido/'));
        exit();
    }
    // seleccion de tipo de envio
    elseif( is_cart() || ( is_checkout() && ! is_wc_endpoint_url( 'order-received' ) ) ) {
        $chosen_shipping  = WC()->session->get('chosen_shipping');
        // Get the 
        $shipping_rate_id = get_shipping_rates_id_from_chosen( $chosen_shipping );
        
        // Set for real the chosen shipping method
        WC()->session->set( 'chosen_shipping_methods', [$shipping_rate_id] );
    }
}


// Agrega un script personalizado al footer 
function add_custom_checkout_script() {
    ?>
    <script type="text/javascript">
        jQuery(function($){
            // Función para ocultar y no requerir campos de facturación según el método de envío
            function custom_hide_and_non_required_checkout_fields() {
                // Obtener el método de envío seleccionado
                var chosen_shipping = '<?php echo WC()->session->get("chosen_shipping"); ?>';

                // Lista de métodos de envío que deben ocultar y no requerir ciertos campos de facturación
                var methods_to_hide_and_non_required_fields = {
                    'pickup': ["billing_address_1_field", "billing_postcode_field", "billing_city_field", "billing_state_field", "billing_country_field", "billing_wooccm9_field"],
                    'delivery': ["billing_state_field", "billing_country_field", "billing_wooccm9_field"],
                    'localEating': ["billing_address_1_field", "billing_postcode_field", "billing_city_field", "billing_state_field", "billing_country_field" , "billing_email_field", "billing_phone_field"],
                    // Añade más métodos según sea necesario
                };

                // Imprimir el tipo de envío en la consola
                console.log('Tipo de envío:', chosen_shipping);

                // Verificar si el método de envío seleccionado requiere ocultar y no requerir campos de facturación
                if (methods_to_hide_and_non_required_fields[chosen_shipping]) {
                    var fields_to_hide_and_non_required = methods_to_hide_and_non_required_fields[chosen_shipping];

                    console.log('Campos a ocultar y no requerir:', fields_to_hide_and_non_required);

					$.each(fields_to_hide_and_non_required, function(index, field) {
                        var fieldElement = document.getElementById(field);
                        if (fieldElement) {
                            fieldElement.style.display = "none";
                            // Verificar si el campo existe y tiene la clase 'validate-required'
                            var $field = $('#' + field);
                            console.log('Campo actual:', $field);
                            if ($field.length && $field.hasClass('validate-required')) {
                                console.log('Ocultando y no requiriendo campo:', field);
                                // Cambiar el valor del campo a "-" excepto para el código postal
                                if (field == 'billing_email_field') {
									$field.find('input').val('emailpruebacomandas@email.com'); 
                                } else if (field == 'billing_postcode_field') {
									$field.find('input').val('13001');
								} else {
									$field.find('input').val('-');
								}
								
                                $field.find('abbr').remove(); // Eliminar el elemento <abbr>
                            }
                        }
                    });
                }

                // Cambiar el contenido del h3 en el formulario de facturación y envío y ocultar otras cosas
                var billingFieldsTitle = $('.woocommerce-checkout .woocommerce-billing-fields h3');
				var detallesFacturacion= $('section.woocommerce-customer-details');
				var tablaExtra= $('tr#tr-billing_wooccm9');
				
                if (chosen_shipping === 'delivery') {
                    billingFieldsTitle.text('Información de Envío a Domicilio');
					tablaExtra.css('display', 'none');
                } else if(chosen_shipping === 'pickup') {
                    billingFieldsTitle.text('Información para Recogida en Local');
					detallesFacturacion.css('display', 'none');
					tablaExtra.css('display', 'none');

                }else{
					billingFieldsTitle.text('Detalles del comensal y Mesa');
					detallesFacturacion.css('display', 'none');
					tablaExtra.css('display', 'block');


				}
            }

            // Ejecutar la función al cargar la página y cuando cambia el método de envío
            $(document).ready(function() {
                custom_hide_and_non_required_checkout_fields();
            });

            $(document.body).on('change', 'input[name^="shipping_method"]', function() {
                console.log('Cambio en el método de envío');
                custom_hide_and_non_required_checkout_fields();
            });
        });
    </script>
    <?php
}

// Agrega el script al footer del checkout
add_action('wp_footer', 'add_custom_checkout_script');



//vaciar campos del formulario cada vez que se hace un pedido

function clear_checkout_fields($input){
    return '';
}
add_filter( 'woocommerce_checkout_get_value' , 'clear_checkout_fields' );


?>