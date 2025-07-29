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
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


</head>

<body>

	<header>
		<span class="logo" style="display: flex; flex-direction: column; align-items: center;">
			<img src="../../Source/img/WhatsApp_Image_2025-06-24_at_6.53.35_PM-removebg-preview (1).png"
				alt="TechDispense" style="height: 40px; border-radius: 12px;">
			<a href="">TechDispense</a>
		</span>



		<nav class="navigation-header">
			<a href="../../index.php"> Volver al Inicio</a>
			<a href="../../View/modulos/producto.php">Comprar el dispensador</a>
			<a href="../../View/modulos/funiona.php">Cómo funciona</a>

		</nav>

		<ul class="social-links">
			<li>
				<a href="../../View/modulos/inicioSesion.html">
					<i class="fa-solid fa-user"></i>
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

	<div class="form-wrapper">
		<div class="form-left">
			<img class="form-video" src="../../Source/img/mecanico-con-plan.jpg" alt="">
		</div>
		<div class="form-right">
			<h1 class="form-title">Instalación del <span style="color: rgb(255, 146, 167);">TecnoDipense</span></h1>
			<form class="form-box" action="../../models/citas.php" method="POST">
				
				    <label class="form-label">Fecha y Hora • Obligatorio</label>
    <input type="datetime-local" class="form-input" name="fecha_hora_instalacion" required>

    <label class="form-label">Dirección • Obligatorio</label>
    <input type="text" class="form-input" name="direccion" required>

    <label class="form-label">Teléfono • Obligatorio</label>
    <div class="form-phone">
        <img src="https://flagcdn.com/w40/mx.png" alt="MX Flag" class="form-flag">
        <input type="tel" class="form-input" name="telefono" placeholder="+52" pattern="\d{10}" maxlength="10" title="Debe tener 10 dígitos" required>
    </div>

    <label class="form-label" for="pedido_id">Selecciona un pedido: • Obligatorio</label>
 
      <select name="pedido_id" id="pedido_id" required>
    <option value="">-- Selecciona un pedido --</option>
    <option value="1">Pedido #1</option>
    <option value="2">Pedido #2</option>
</select>

   <br><br>

		
<style>

  label.form-label::after {
    content: " • Obligatorio";
    color: #d33;
    font-weight: 700;
    margin-left: 5px;
  }
  select#pedido_id {
    width: 100%;
    max-width: 300px;
    padding: 8px 12px;
    font-size: 1rem;
    border: 2px solid #ccc;
    border-radius: 6px;
    transition: border-color 0.3s ease;
    cursor: pointer;
  }
 
</style>
				<button type="submit" class="form-button">Agendar Instalación →</button>

				<p class="form-note">
					<a href="#">Conoce más</a> sobre cómo usamos tus datos en TechDispense.
				</p>
			</form>
		</div>
	</div> <br><br>

	<footer class="footer-container">
		<div class="footer-top">
		</div>

		<div class="footer-bottom">
			<div></div>
			<p class="footer-copyright">
				&copy;2024 TechDispense. Diseñado con amor para marcas que quieren crecer.
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

	<div class="chatbot-container" id="chatbot">
		<div class="chatbot-header">
			<p>¿Tienes dudas?</p>
			<button id="chatbot-toggle">
				<i class="fa-solid fa-xmark"></i>
			</button>
		</div>
		<div class="chatbot-body" id="chat-body">
			<div class="chat-message bot">
				<p>¡Hola! ¿Quieres saber cómo funciona TechDispense?</p>
			</div>
		</div>
		<form id="chat-form" class="chatbot-input">
			<input type="text" id="chat-input" placeholder="Escribe tu mensaje..." required />
			<button type="submit"><i class="fa-solid fa-paper-plane"></i></button>
		</form>
	</div>

	<button id="chatbot-button">
		<i class="fa-solid fa-comments"></i>
	</button>


	<script src="../../View/js/chatboot.js"></script>
	<script src="../../View/js/menu.js"></script>
</body>

</html>