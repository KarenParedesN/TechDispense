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
				<link rel="stylesheet" href="../../View/css/chatboot.css" />
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
				<a href="../../index.php">Volver al Inicio</a>
				<a href="../../View/modulos/funiona.php">Cómo funciona</a>
				<a href="../../View/modulos/producto.php">Comprar el dispensador</a>
			</nav>

		</div>
	</header>


	<div class="registro-body">
		<div class="registro-container">
			<h1 class="registro-title">Registro de Sesión</h1>

			<div class="registro-links">
				<a href="../../View/modulos/inicioSesion.php">Iniciar sesión</a> <br><br>

		<form id="form-registro" action="../../models/registro.php" method="POST">
	<div class="input-group">
		<span class="error-msg" id="error-nombre"></span>
		<label for="nombre">Nombre</label>
		<input class="registro-input" type="text" id="nombre" name="nombre">
	</div>

	<div class="input-group">
		<span class="error-msg" id="error-apellido_paterno"></span>
		<label for="apellido_paterno">Apellido paterno</label>
		<input class="registro-input" type="text" id="apellido_paterno" name="apellido_paterno">
	</div>

	<div class="input-group">
		<span class="error-msg" id="error-apellido_materno"></span>
		<label for="apellido_materno">Apellido materno</label>
		<input class="registro-input" type="text" id="apellido_materno" name="apellido_materno">
	</div>

	<div class="input-group">
		<span class="error-msg" id="error-correo"></span>
		<label for="correo">Correo electrónico</label>
		<input class="registro-input" type="email" id="correo" name="correo">
	</div>

	<div class="input-group">
		<span class="error-msg" id="error-contrasena"></span>
		<label for="contrasena">Código secreto</label>
		<input class="registro-input" type="password" id="contrasena" name="contrasena">
	</div>

	<button class="registro-button" type="submit">Registrate-></button>
</form>
	<div class="registro-footer">
					Al registrarte, aceptas las
					<a href="#">Condiciones de uso</a> y la <a href="#">Política de privacidad</a>.
				</div>
			</div>
		</div>
	</div> <br><br><br>

<br>

<script> let estadoConversacion = "";

  function obtenerRespuesta(mensaje) {
    mensaje = mensaje.toLowerCase();

  
    if (
      mensaje.includes("problema") ||
      mensaje.includes("no funciona") ||
      mensaje.includes("soporte") ||
      mensaje.includes("fallo")
    ) {
      if (estadoConversacion === "soporte") {
        return "📌 Recuerda que puedes escribirnos directamente aquí 👉 <a href='https://wa.me/5211234567890' target='_blank'>WhatsApp Soporte</a> o en el formulario 👉 <a href='#contacto'>Contáctanos</a>.";
      }
      estadoConversacion = "soporte";
      return "🚨 Entiendo, no te preocupes. Nuestro equipo está listo para ayudarte. ¿Deseas que un técnico te contacte por WhatsApp?\n👉 <a href='https://wa.me/5211234567890' target='_blank'>Solicitar ayuda por WhatsApp</a>\nO si prefieres, puedes dejar tu caso aquí 👉 <a href='#contacto'>Contáctanos</a>";
    }

    if (
      mensaje.includes("hola") ||
      mensaje.includes("quiero saber") ||
      mensaje.includes("dispensador")
    ) {
      estadoConversacion = "esperando_producto";
      return "¡Hola! ¿Sabías que nuestros dispensadores inteligentes pueden ayudarte a AUMENTAR tus ventas desde el primer día? 🎯 Cuéntame, ¿qué te gustaría vender? (Ej: Toallas sanitarias femeninas (pack individual), Dulces surtidos para todos los gustos, Detergente en cápsulas, Snacks salados en empaque individual, Bebida energética fría. y más...)";
    }

    if (estadoConversacion === "esperando_producto") {
      estadoConversacion = "esperando_ubicacion";
      return "¡Excelente elección! 😎 Nuestro sistema combina automatización + publicidad con códigos QR personalizados que atraen más clientes.\n¿Dónde planeas colocarlo? Tenemos modelos para:\n- Mostrador\n- Escritorio\n- Pared\n- Entrada\n- Evento";
    }

    if (estadoConversacion === "esperando_ubicacion") {
      if (mensaje.includes("evento")) {
        estadoConversacion = "finalizar_venta";
        return "🔝 El modelo <strong>EventPro QR</strong> es ideal para ferias o eventos con alto tráfico. Robusto, llamativo y diseñado para atraer clientes.\n👉 Aumenta tus ventas aquí: <a href='../../View/modulos/producto.php' target='_blank'>Comprar EventPro</a>";
      }
      if (mensaje.includes("entrada")) {
        estadoConversacion = "finalizar_venta";
        return "🎯 El modelo <strong>EntryStand QR</strong> es perfecto para captar la atención en entradas. Alta visibilidad y tecnología lista para vender.\n👉 Adquiérelo aquí: <a href='../../View/modulos/producto.php' target='_blank'>Comprar EntryStand</a>";
      }
      if (mensaje.includes("mostrador")) {
        estadoConversacion = "finalizar_venta";
        return "✅ El <strong>MiniDisplay QR</strong> es perfecto para mostradores pequeños. Llama la atención sin ocupar espacio.\n👉 Cómpralo ahora: <a href='../../View/modulos/producto.php' target='_blank'>Comprar MiniDisplay</a>";
      }
      if (mensaje.includes("escritorio")) {
        estadoConversacion = "finalizar_venta";
        return "🧠 El modelo <strong>DeskDisplay QR</strong> es ideal para escritorios de oficina. Profesional, elegante y útil.\n👉 Ordénalo aquí: <a href='../../View/modulos/producto.php' target='_blank'>Comprar DeskDisplay</a>";
      }
      if (mensaje.includes("pared")) {
        estadoConversacion = "finalizar_venta";
        return "📌 El modelo <strong>WallMount QR</strong> se instala en cualquier pared. Perfecto para ahorrar espacio y seguir vendiendo.\n👉 Consíguelo aquí: <a href='../../View/modulos/producto.php' target='_blank'>Comprar WallMount</a>";
      }
      if (mensaje.includes("gracias") || mensaje.includes("muchas")) {
        estadoConversacion = "finalizar_venta";
        return "Estoy aquí para ayudarte cuando lo necesites 💖";
      }
      return "¿Podrías decirme si el espacio es mostrador, escritorio, pared, entrada o evento?";
    }

    if (mensaje.includes("me interesa") || mensaje.includes("quiero comprar")) {
      estadoConversacion = "finalizar_venta";
      return "🎉 ¡Excelente decisión! Aquí está el enlace para adquirir tu dispensador:\n👉  <a href='../../View/modulos/producto.php' target='_blank'>Ir a tienda</a>Ir a tienda</a>\n¿Deseas que un asesor te contacte para ayudarte con la instalación o personalizar el QR?";
    }

    if (mensaje.includes("ayuda")) {
      estadoConversacion = "esperando_ayuda";
      return "🛠️ Claro, puedo ayudarte con:\n1. Elegir el mejor modelo\n2. Comprar\n3. Instalar\n4. Personalizar tu QR\n\nResponde con el número o cuéntame qué necesitas 😊";
    }

    if (estadoConversacion === "esperando_ayuda") {
      if (mensaje.includes("1")) {
        estadoConversacion = "esperando_producto";
        return "Perfecto. ¿Qué te gustaría vender? Así te recomendaré el modelo más adecuado.";
      }
      if (mensaje.includes("2")) {
        estadoConversacion = "finalizar_venta";
        return "Aquí puedes explorar y comprar nuestros modelos 👉 <a href='../../View/modulos/producto.php' target='_blank'>Ir a tienda</a>";
      }
      if (mensaje.includes("3")) {
        estadoConversacion = "soporte";
        return "Para ayudarte con la instalación, por favor visita 👉 Escribenos por WhatsApp 👉 <a href='https://wa.me/5211234567890' target='_blank'>Soporte técnico</a>";
      }
      if (mensaje.includes("4")) {
        estadoConversacion = "soporte";
        return "Para personalizar tu código QR, descarga la aplicación";
      }
      return "Indícame una opción del 1 al 4 o dime en tus palabras qué necesitas 😊";
    }

    if (mensaje.includes("gracias") || mensaje.includes("muchas gracias")) {
      estadoConversacion = "";
      return "¡Gracias a ti por confiar en TechDispense! 😊 Si necesitas algo más, estaré aquí 💖";
    }

    return "🤔 No entendí eso. Puedes escribirme por ejemplo: 'quiero aumentar mis ventas', 'qué modelo me conviene', o 'tengo un problema'. Estoy aquí para ayudarte.";
  }

  const chatForm = document.getElementById("chat-form");
  const chatInput = document.getElementById("chat-input");
  const chatBody = document.getElementById("chat-body");
  const chatbot = document.getElementById("chatbot");
  const botonAbrir = document.getElementById("boton-abrir-chat");
  const botonCerrar = document.getElementById("chatbot-toggle");

  function agregarMensaje(texto, tipo) {
    const div = document.createElement("div");
    div.classList.add("chat-message", tipo);
    div.innerHTML = `<p>${texto}</p>`;
    chatBody.appendChild(div);
    chatBody.scrollTop = chatBody.scrollHeight;
  }

  chatForm.addEventListener("submit", function (e) {
    e.preventDefault();
    const mensajeUsuario = chatInput.value.trim();
    if (!mensajeUsuario) return;
    agregarMensaje(mensajeUsuario, "user");
    const respuestaBot = obtenerRespuesta(mensajeUsuario);
    setTimeout(() => {
      agregarMensaje(respuestaBot, "bot");
    }, 500);
    chatInput.value = "";
  });

  botonCerrar.addEventListener("click", () => {
    chatbot.style.display = "none";
    botonAbrir.style.display = "block";
  });

  botonAbrir.addEventListener("click", () => {
    chatbot.style.display = "block";
    botonAbrir.style.display = "none";
  });
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


	<script src="../../View/js/chatboot.js"></script>
	<script src="../../View/js/menu.js"></script>
</body>

</html>

<style>


	.error-msg {
		color: red;
		font-size: 0.85em;
		margin-bottom: 4px;
		display: block;
	}

	.valid {
		border: 3px solid limegreen;
	}

	.invalid {
		border: 3px solid red;
	}

	
</style>


<script>
const form = document.getElementById('form-registro');

const campos = {
	nombre: {
		elemento: document.getElementById('nombre'),
		error: document.getElementById('error-nombre'),
		validar: valor => valor.trim() !== '',
		mensaje: 'El nombre es obligatorio'
	},
	apellido_paterno: {
		elemento: document.getElementById('apellido_paterno'),
		error: document.getElementById('error-apellido_paterno'),
		validar: valor => valor.trim() !== '',
		mensaje: 'El apellido paterno es obligatorio'
	},
	apellido_materno: {
		elemento: document.getElementById('apellido_materno'),
		error: document.getElementById('error-apellido_materno'),
		validar: valor => valor.trim() !== '',
		mensaje: 'El apellido materno es obligatorio'
	},
	correo: {
		elemento: document.getElementById('correo'),
		error: document.getElementById('error-correo'),
		validar: valor => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(valor),
		mensaje: 'Correo electrónico inválido'
	},
	contrasena: {
		elemento: document.getElementById('contrasena'),
		error: document.getElementById('error-contrasena'),
		validar: valor => valor.length >= 7,
		mensaje: 'La contraseña debe tener al menos 7 caracteres'
	}
};

Object.values(campos).forEach(campo => {
	campo.elemento.addEventListener('input', () => {
		validarCampo(campo);
	});
});

function validarCampo(campo) {
	const valor = campo.elemento.value;
	if (!campo.validar(valor)) {
		campo.elemento.classList.add('invalid');
		campo.elemento.classList.remove('valid');
		campo.error.textContent = campo.mensaje;
		return false;
	} else {
		campo.elemento.classList.add('valid');
		campo.elemento.classList.remove('invalid');
		campo.error.textContent = '';
		return true;
	}
}


form.addEventListener('submit', function (e) {
	e.preventDefault();
	let todoBien = true;
	Object.values(campos).forEach(campo => {
		if (!validarCampo(campo)) {
			todoBien = false;
		}
	});

	if (todoBien) {
		form.submit();
	}
});
</script>

