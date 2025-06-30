let preguntas = [];

// Elementos del DOM
const listaPreguntas = document.getElementById("listaPreguntas");
const form = document.getElementById("formPregunta");
const input = document.getElementById("textoPregunta");

// 🟡 Cargar preguntas desde localStorage o archivo
function cargarPreguntas() {
  const guardadas = localStorage.getItem("preguntas");
  if (guardadas) {
    preguntas = JSON.parse(guardadas);
    mostrarPreguntas();
  } else {
    fetch("preguntas.json")
      .then(res => res.json())
      .then(data => {
        preguntas = data;
        mostrarPreguntas();
      });
  }
}

// 🟢 Mostrar preguntas en lista editable
function mostrarPreguntas() {
  listaPreguntas.innerHTML = "";

  preguntas.forEach((p, index) => {
    const li = document.createElement("li");
    li.innerHTML = `
      <input value="${p.pregunta}" data-index="${index}" class="editable" />
      <button data-index="${index}" class="btn-borrar">❌</button>
    `;
    listaPreguntas.appendChild(li);
  });

  // Listeners para edición y borrado
  document.querySelectorAll(".editable").forEach(input => {
    input.addEventListener("change", e => {
      const i = e.target.dataset.index;
      editarPregunta(i, e.target.value);
    });
  });

  document.querySelectorAll(".btn-borrar").forEach(btn => {
    btn.addEventListener("click", e => {
      const i = e.target.dataset.index;
      borrarPregunta(i);
    });
  });

  guardarEnLocal();
}

// 🔵 Agregar nueva pregunta
form.addEventListener("submit", e => {
  e.preventDefault();
  const texto = input.value.trim();
  if (texto === "") return;

  if (preguntas.some(p => p.pregunta.toLowerCase() === texto.toLowerCase())) {
    alert("Ya existe una pregunta similar.");
    return;
  }

  preguntas.push({ id: "p" + Date.now(), pregunta: texto });
  input.value = "";
  mostrarPreguntas();
});

// ✏️ Editar pregunta
function editarPregunta(index, nuevoTexto) {
  preguntas[index].pregunta = nuevoTexto;
  guardarEnLocal();
}

// ❌ Borrar pregunta
function borrarPregunta(index) {
  preguntas.splice(index, 1);
  mostrarPreguntas();
}

// 💾 Guardar en localStorage
function guardarEnLocal() {
  localStorage.setItem("preguntas", JSON.stringify(preguntas));
}

// 📦 Descargar preguntas como JSON
function guardarPreguntas() {
  const contenido = JSON.stringify(preguntas, null, 2);
  const blob = new Blob([contenido], { type: "application/json" });
  const enlace = document.createElement("a");
  enlace.href = URL.createObjectURL(blob);
  enlace.download = "preguntas.json";
  enlace.click();
}

// 📋 Mostrar respuestas almacenadas
function mostrarRespuestas() {
  const datos = JSON.parse(localStorage.getItem("respuestas")) || [];
  const tabla = document.getElementById("tablaRespuestas");
  if (!tabla) return;

  if (datos.length === 0) {
    tabla.innerHTML = "<tr><td>No hay respuestas todavía.</td></tr>";
    return;
  }

  const encabezados = Object.keys(datos[0]);
  let html = "<tr>" + encabezados.map(h => `<th>${h}</th>`).join("") + "</tr>";

  datos.forEach(resp => {
    html += "<tr>" + encabezados.map(h => `<td>${resp[h] || ""}</td>`).join("") + "</tr>";
  });

  tabla.innerHTML = html;
}

// ⬇️ Descargar respuestas como CSV
function descargarCSV() {
  const datos = JSON.parse(localStorage.getItem("respuestas")) || [];
  if (datos.length === 0) {
    alert("No hay respuestas para descargar.");
    return;
  }

  const encabezados = Object.keys(datos[0]);
  const filas = datos.map(obj =>
    encabezados.map(e => `"${(obj[e] || "").replace(/"/g, '""')}"`).join(",")
  );
  const csv = [encabezados.join(","), ...filas].join("\n");

  const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
  const link = document.createElement("a");
  link.href = URL.createObjectURL(blob);
  link.download = "respuestas.csv";
  link.click();
}

// ⏬ Inicialización
cargarPreguntas();
mostrarRespuestas();

// ⏫ Exportar funciones al HTML si las usas desde botones inline
window.guardarPreguntas = guardarPreguntas;
window.descargarCSV = descargarCSV;
