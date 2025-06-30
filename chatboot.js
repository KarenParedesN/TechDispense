 let estadoConversacion = "";

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
      return "¡Hola! ¿Sabías que nuestros dispensadores inteligentes pueden ayudarte a AUMENTAR tus ventas desde el primer día? 🎯 Cuéntame, ¿qué te gustaría vender? (Ej: dulces, gel, folletos promocionales...)";
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