import React from "react";
import iconoPayment from "./assets/tron-trx-logo.svg";

const PaymentMethodEdit = () => {
    return (
        <div>
            <p>Formulario de pago de prueba</p>
        </div>
    );
};

const PaymentMethodContent = () => {
    return <p>Realiza el pago de este pedido utilizando TRON (TRX)</p>;
};

if (window.wc?.wcBlocksRegistry?.registerPaymentMethod) {
    console.log("Registrando el método de pago...");

    const methodTitle = "Pagar con TRX";
    const icon = iconoPayment;

    const PaymentGateway = {
        name: "trx_payments",
        label: React.createElement(
            "span",
            null,
            React.createElement("img", {
                src: icon,
                alt: methodTitle,
                style: { width: 48, height: 48 },
            }),
            "  " + methodTitle
        ),
        content: <PaymentMethodContent />,
        edit: <PaymentMethodEdit />,
        canMakePayment: () => true,
        ariaLabel: "Pagar con TRX",
        supports: {
            features: ["products"],
        },
    };

    window.wc.wcBlocksRegistry.registerPaymentMethod(PaymentGateway);
} else {
    console.error("No se encontró wcBlocksRegistry o registerPaymentMethod");
    alert("¡No se pudo registrar el método de pago!");
}
