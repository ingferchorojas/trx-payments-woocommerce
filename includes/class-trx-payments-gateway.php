<?php

class WC_Gateway_MiMetodo extends WC_Payment_Gateway {

    public $instructions;  // Definir la propiedad para instrucciones

    public function __construct() {
        $this->id = 'trx_payments';
        $this->has_fields = true;
        $this->method_title = 'Mi Método';
        $this->method_description = 'Método de pago personalizado para WooCommerce Blocks';
        $this->instructions = ''; // Instrucciones iniciales

        // Inicialización de los campos del formulario y ajustes
        $this->init_form_fields();
        $this->init_settings();

        // Actualizar opciones del gateway desde el panel de administración
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options']);
    }

    /**
     * Procesar el pago
     * 
     * @param int $order_id
     * @return array
     */
    

    public function process_payment($order_id) {
        $order = wc_get_order($order_id);
    
        $redirect_url = plugins_url('payment.php', __FILE__) . '?order_id=' . $order_id;
    
        return [
            'result' => 'success',
            'redirect' => $redirect_url
        ];
    }
    
    
    

    // Inicialización de campos de formulario (opcional)
    public function init_form_fields() {
        $this->form_fields = [
            'enabled' => [
                'title'   => 'Habilitar/Deshabilitar',
                'type'    => 'checkbox',
                'label'   => 'Habilitar Mi Método de Pago',
                'default' => 'yes',
            ],
            'instructions' => [
                'title'       => 'Instrucciones',
                'type'        => 'textarea',
                'description' => 'Instrucciones que aparecerán al cliente durante el pago.',
                'default'     => $this->instructions,
            ],
        ];
    }
}

// Registrar el gateway de pago personalizado
add_filter('woocommerce_payment_gateways', function($gateways) {
    $gateways[] = 'WC_Gateway_MiMetodo';  // Añadir el gateway personalizado
    return $gateways;
});
