<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TechDispense </title>
  <link rel="icon" href="/img/favicon.ico" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="../../View/css/styles.css" />
  <link rel="stylesheet" href="../../View/css/footer.css" />
  <link rel="stylesheet" href="../../View/css/chatbot.css" />
  <style>
    .registro-input.error {
      border-color: red;
    }

    .registro-input.success {
      border-color: #00c853;
    }

    .mensaje-error {
      color: red;
      font-size: 0.9em;

      display: block;
      min-height: 18px;
    }


    #errorGeneral {
      background-color: #ffdddd;
      color: red;
      padding: 10px;

      border-radius: 5px;
      display: none;
    }
  </style>
</head>

<body>

  <header>
    <span class="logo" style="display: flex; flex-direction: column; align-items: center;">
      <img src="../../Source/img/WhatsApp_Image_2025-06-24_at_6.53.35_PM-removebg-preview (1).png" alt="TechDispense"
        style="height: 40px; border-radius: 12px;" />
      <a href="">TechDispense</a>
    </span>

    <nav class="navigation-header">
      <a href="../../index.php"> Volver al Inicio</a>
      <a href="../../View/modulos/producto.php">Comprar el dispensador</a>
      <a href="../../View/modulos/funiona.php">Cómo funciona</a>
    </nav>

    <ul class="social-links">
      <li>
      
      </li>

      <li>
      
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
    </div>
  </header>

  <div class="registro-body">
    <div class="registro-container">
      <h1 class="registro-title">Inicio de sesión de Sesión</h1>

      <div class="registro-links">
        <a href="../../View/modulos/registroSesion.php">Registrarse</a> <br />

        <form id="formulario" novalidate>
          <br>
          <div id="errorGeneral"></div>

          <label for="correo" class="registro-label">Correo electrónico</label>
          <small id="errorCorreo" class="mensaje-error"></small>
          <input type="email" id="correo" name="correo" class="registro-input" placeholder="Correo electrónico" />

          <label for="contrasena" class="registro-label">Código secreto</label>
          <small id="errorContrasena" class="mensaje-error"></small>
          <input type="password" id="contrasena" name="contrasena" class="registro-input"
            placeholder="Código secreto" />

          <button type="submit" class="registro-button">Inicia sesión →</button>
        </form>

        <div class="registro-footer">
          Al registrarte, aceptas las
          <a href="#">Condiciones de uso</a> y la <a href="#">Política de privacidad</a>.
        </div>
      </div>
    </div>
  </div>

  <footer class="footer-container">
    <div class="footer-top"></div>

    <div class="footer-bottom">
      <div></div>
      <p class="footer-copyright">
        &copy;2024 <span style="color: rgb(255, 174, 143);">TechDispense</span>. Diseñado con amor para marcas que
        quieren crecer.
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

  <script>const formulario = document.getElementById("formulario");
    const correo = document.getElementById("correo");
    const contrasena = document.getElementById("contrasena");

    const errorCorreo = document.getElementById("errorCorreo");
    const errorContrasena = document.getElementById("errorContrasena");
    const errorGeneral = document.getElementById("errorGeneral");

    function validarCorreo() {
      const correoVal = correo.value.trim();
      const correoRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!correoRegex.test(correoVal)) {
        correo.classList.add("error");
        correo.classList.remove("success");
        errorCorreo.textContent = "Correo electrónico inválido";
        return false;
      } else {
        correo.classList.remove("error");
        correo.classList.add("success");
        errorCorreo.textContent = "";
        return true;
      }
    }

    function validarContrasena() {
      if (contrasena.value.trim().length < 7) {
        contrasena.classList.add("error");
        contrasena.classList.remove("success");
        errorContrasena.textContent = "La contraseña debe tener al menos 7 caracteres";
        return false;
      } else {
        contrasena.classList.remove("error");
        contrasena.classList.add("success");
        errorContrasena.textContent = "";
        return true;
      }
    }

    correo.addEventListener("input", () => {
      validarCorreo();
      errorGeneral.style.display = "none";
      errorGeneral.textContent = "";
    });

    contrasena.addEventListener("input", () => {
      validarContrasena();
      errorGeneral.style.display = "none";
      errorGeneral.textContent = "";
    });

    formulario.addEventListener("submit", async function (e) {
      e.preventDefault();

      const correoValido = validarCorreo();
      const contrasenaValida = validarContrasena();

      if (!correoValido || !contrasenaValida) {
        return;
      }

      const formData = new FormData();
      formData.append("correo", correo.value.trim());
      formData.append("contrasena", contrasena.value.trim());

      try {
        const response = await fetch("../../models/inicioSesion.php", {
          method: "POST",
          body: formData,
        });

        const result = await response.json();

        if (result.exito) {

          const rol = Number(result.id_rol);
          if (rol === 1) {
            window.location.href = '../../View/modulos/administrador/productos.php';
          } else if (rol === 2) {
            window.location.href = '../../View/modulos/producto.php';

          }
        } else {
          errorGeneral.textContent = result.mensaje || "Error desconocido";
          errorGeneral.style.display = "block";
        }
      } catch (error) {
        errorGeneral.textContent = "Error en el servidor, intenta más tarde.";
        errorGeneral.style.display = "block";
        console.error("Error fetch:", error);
      }
    });
  </script>
</body>

</html>