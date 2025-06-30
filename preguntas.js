fetch('preguntas.json')
  .then(res => res.json())
  .then(preguntas => {
const formulario = document.getElementById('formulario');
const btnEnviar = document.getElementById('enviar');

function mostrarPreguntas() {
  formulario.innerHTML = preguntas.map(p => `
    <label for="${p.id}">${p.pregunta}</label>
    <input type="text" id="${p.id}" name="${p.id}" required />
  `).join('');
}

btnEnviar.addEventListener('click', () => {
  const respuestas = {};
  preguntas.forEach(p => {
    const valor = document.querySelector(`[name="${p.id}"]`).value;
    respuestas[p.pregunta] = valor;
  });

  const respuestasGuardadas = JSON.parse(localStorage.getItem('respuestas')) || [];
  respuestasGuardadas.push(respuestas);
  localStorage.setItem('respuestas', JSON.stringify(respuestasGuardadas));

  alert('Gracias por responder');
  formulario.reset();
});

mostrarPreguntas();
  })
  .catch(error => {
    console.error('Error cargando preguntas:', error);
  });

