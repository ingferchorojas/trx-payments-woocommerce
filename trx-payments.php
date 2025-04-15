<?php
/**
 * Plugin Name: Mi Método de Pago para WooCommerce Blocks
 * Description: Método de pago personalizado compatible con WooCommerce Checkout Blocks.
 * Author: Fernando
 * Version: 1.0.0
 */

defined( 'ABSPATH' ) || exit;

add_action('plugins_loaded', function () {
    if (!class_exists('WooCommerce')) {
        return;
    }

    include_once __DIR__ . '/includes/class-trx-payments-gateway.php';
});

// Agregar soporte para los bloques de WooCommerce
add_filter('woocommerce_blocks_get_payment_method', function($methods) {
    $methods[] = 'trx_payments'; // Aquí se asegura que tu método de pago se agregue a los métodos de pago disponibles en el bloque de checkout
    return $methods;
});

add_action('enqueue_block_assets', function () {
    if (!function_exists('is_checkout')) {
        return;
    }

    if (is_checkout()) {
        wp_enqueue_script(
            'trx-payment-gateway',
            plugins_url('build/index.js', __FILE__),
            ['wc-blocks-registry', 'wp-element', 'wp-i18n'],
            null,
            true
        );
    }
});
