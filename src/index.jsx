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

const methodName = "trx_payments";

const registerTrxPaymentMethod = () => {
    if (!window.wc?.wcBlocksRegistry?.registerPaymentMethod) {
        console.warn("registerPaymentMethod no disponible aún");
        return;
    }

    if (window.trxPaymentRegistered) return;

    console.log("Registrando el método de pago TRX...");

    const methodTitle = "Pagar con TRX";
    const icon = iconoPayment;

    const PaymentGateway = {
        name: methodName,
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
    window.trxPaymentRegistered = true;

    // Forzar que aparezca si no lo ve Woo
    if (
        window.wc?.wcSettings?.availablePaymentMethods &&
        !window.wc.wcSettings.availablePaymentMethods.find(
            (m) => m.name === methodName
        )
    ) {
        window.wc.wcSettings.availablePaymentMethods.push({ name: methodName });
    }

    // Forzar recarga del checkout
    if (window.wc?.blocksCheckout?.refreshCheckout) {
        console.log("Forzando refresh del checkout...");
        window.wc.blocksCheckout.refreshCheckout();
    } else {
        console.warn("No se pudo refrescar el checkout");
    }
};

// Esperar a que Woo esté listo para registrar métodos
const waitForWooAndRegister = () => {
    if (document.readyState === "complete") {
        registerTrxPaymentMethod();
    } else {
        window.addEventListener("load", registerTrxPaymentMethod);
    }

    // Intentar por si Woo se carga asíncronamente
    const interval = setInterval(() => {
        registerTrxPaymentMethod();
        if (window.trxPaymentRegistered) clearInterval(interval);
    }, 500);

    // También al volver hacia atrás
    window.addEventListener("popstate", registerTrxPaymentMethod);
};

waitForWooAndRegister();
