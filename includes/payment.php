<?php
ob_start();

try {
    // Cargar WordPress
    $wp_path = dirname(__FILE__, 5) . '/wp-load.php';
    if (!file_exists($wp_path)) {
        throw new Exception('wp-load.php not found.');
    }
    require_once($wp_path);

    // Verificamos el parámetro
    if (!isset($_GET['order_id'])) {
        wp_die('Order ID not provided.');
    }

    $order_id = intval($_GET['order_id']);
    $order = wc_get_order($order_id);
    if (!$order) {
        wp_die('Order not found.');
    }

    // Datos del pedido
    $order_amount = $order->get_total();
    $order_date = $order->get_date_created()->date('F j, Y');

    // Pasamos los datos al JavaScript
    $order_data = json_encode([
        'order_id' => $order_id,
        'order_amount' => $order_amount,
        'order_date' => $order_date,
        'currency_symbol' => get_woocommerce_currency_symbol(),
    ]);

    // HTML y JavaScript
    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>' . esc_html(get_bloginfo('name')) . '</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Cargar la librería QRCode correcta -->
        <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    </head>
    <body class="d-flex justify-content-center align-items-center" style="height: 100vh; margin: 0;">
        <div class="card shadow-lg p-4" style="max-width: 500px; width: 100%;">
            <h2 class="card-title text-center">Order #' . esc_html($order_id) . '</h2>
            <p><strong>Date:</strong> ' . esc_html($order_date) . '</p>
            <p><strong>Total:</strong> ' . get_woocommerce_currency_symbol() . esc_html($order_amount) . '</p>
            <p><strong>Payment Type:</strong> TRON (TRX)</p>
            <hr>
            <div class="d-flex justify-content-center position-relative">
                <!-- Logo de TRON centrado sobre el QR -->
                <img src="../src/assets/tron-trx-logo.png" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 40px; height: auto;" alt="TRON logo"/>
                <!-- El canvas para el QR -->
                <canvas id="qrcodeCanvas"></canvas> 
            </div>
        </div>

        <script src="js/qr-generate.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>';


} catch (Exception $e) {
    echo 'Error: ' . esc_html($e->getMessage());
}

ob_end_flush();
?>
