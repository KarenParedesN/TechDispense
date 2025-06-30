


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TechDispense </title>
    <link rel="icon" href="/img/favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../View/css/styles.css" />
    <link rel="stylesheet" href="../../View/css/footer.css" />
    <link rel="stylesheet" href="../../View/css/precios.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
   <script src="https://www.paypal.com/sdk/js?client-id=AZe2LD2RUd3zS2yQYwM7O7Izbg9RIXipKYcvSNCEN2kVO2czFTPcVADxK4fisZGuU8sdrPO3GxT6g793&currency=MXN"></script>


</head>

<body>

    <header>
        <span class="logo" style="display: flex; flex-direction: column; align-items: center;">
            <img src="../../Source/img/WhatsApp_Image_2025-06-24_at_6.53.35_PM-removebg-preview (1).png"
                alt="TechDispense" style="height: 40px; border-radius: 12px;">
            <a href="">TechDispense</a>
        </span>



        <nav class="navigation-header">
            <a href="../../index.html"> Volver al Inicio</a>
            <a href="../../View/modulos/producto.html">Comprar el dispensador</a>
            <a href="../../View/modulos/funiona.html">Cómo funciona</a>

        </nav>

        <ul class="social-links">
            <li>
                <a href="../../View/modulos/inicioSesion.html">
                    <i class="fa-solid fa-user"></i>
                </a>
            </li>
            <li>
                <a
                    href="https://www.facebook.com/people/TechDispense-Dispensadores/pfbid02d6hs26zpvTh9NDthMHxT6PuSg2rc7343TxhEnTPivGwn7GfXcmUurYjGoNBhq5yul/?rdid=LSBmSX0IG0erdEmb&share_url=https%3A%2F%2Fwww.facebook.com%2Fshare%2F19MEckERRv%2F">
                    <i class="fa-brands fa-facebook"></i>
                </a>
            </li>
            <li>
                <a href="https://www.instagram.com/techdispensedispensadores/#">
                    <i class="fa-brands fa-instagram"></i>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa-solid fa-cart-shopping"></i>(1)
                </a>
            </li>
        </ul>

        <button class="btn-menu-responsive">
            <i class="fa-solid fa-bars-staggered"></i>
        </button>

        <div class="menu-mobile">
            <div class="btn-close">
                <i class="fa-solid fa-x"></i>
            </div>
            <span class="logo">
                <a href="/">TechDispense</a>
            </span>

            <nav class="navigation-mobile">
                <a href="../../index.html">Inicio</a>
                <a href="../../View/modulos/funiona.html">Cómo funciona</a>
                <a href="../../View/modulos/producto.html">Comprar el dispensador</a>
            </nav>

        </div>
    </header>

    <div id="paypal-button-container">

    </div>
<script>
paypal.Buttons({
    style: {
        color: 'blue',
        shape: 'pill',
        label: 'pay'
    },
    createOrder: function(data, actions) {
        return actions.order.create({
            purchase_units: [{
                amount: {
                    value: '100.00' 
                }
            }]
        });
    },
  onApprove: function(data, actions) {
  return actions.order.capture().then(function(detalles) {
    console.log(detalles);
    window.open("../../View/modulos/gracias.php", "_blank");
  });
}

        },

    onCancel: function(data) {
    alert("Pago cancelado");
    console.log(data);
    

    }
}).render('#paypal-button-container');
</script>




    <footer class="footer-container">
        <div class="footer-top">
        </div>

        <div class="footer-bottom">
            <div></div>
            <p class="footer-copyright">
                &copy;2024 <span style="color: rgb(255, 174, 143);">TechDispense</span>. Diseñado con amor para marcas
                que quieren crecer.
            </p>
            <ul class="footer-social-links">
                <li>
                    <a
                        href="https://www.facebook.com/people/TechDispense-Dispensadores/pfbid02d6hs26zpvTh9NDthMHxT6PuSg2rc7343TxhEnTPivGwn7GfXcmUurYjGoNBhq5yul/?rdid=LSBmSX0IG0erdEmb&share_url=https%3A%2F%2Fwww.facebook.com%2Fshare%2F19MEckERRv%2F">
                        <i class="fa-brands fa-facebook"></i>
                    </a>
                </li>
                <li>
                    <a href="https://www.instagram.com/techdispensedispensadores/#">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fa-brands fa-whatsapp"></i>
                    </a>
                </li>
            </ul>
        </div>
    </footer>

    <script src="../../View/js/menu.js"></script>
</body>

</html>