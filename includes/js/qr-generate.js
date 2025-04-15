document.addEventListener("DOMContentLoaded", function () {
    // Dirección TRON ficticia
    var tronAddress = "TXYZ1234567890EXAMPLEDUMMYADDRESS";

    QRCode.toCanvas(
        document.getElementById("qrcodeCanvas"),
        tronAddress,
        {
            width: 250,
            color: {
                dark: "#000000",
                light: "#ffffff",
            },
        },
        function (error) {
            if (error) console.error(error);
        }
    );
});
