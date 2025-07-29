/* Este archivo debe estar colocado en la carpeta raíz del sitio.
 * 
 * Cualquier cambio en el contenido de este archivo hace que el service
 * worker se reinstale. */
/**
 * Cambia el número de la versión cuando cambia el contenido de los
 * archivos.
 * 
 * El número a la izquierda del punto (.), en este caso <q>1</q>, se
 * conoce como número mayor y se cambia cuando se realizan
 * modificaciones grandes o importantes.
 * 
 * El número a la derecha del punto (.), en este caso <q>00</q>, se
 * conoce como número menor y se cambia cuando se realizan
 * modificaciones menores.
 */
const VERSION = "1.5"
/** Nombre del archivo de cache. */
const CACHE = "TechDispense"
/**
 * Archivos requeridos para que la aplicación funcione fuera de
 * línea.
 */
const ARCHIVOS = [
  "agregaDispensador.html",
  "agregaUsuario.html",
  "dispensadores.html",
  "dispensadoresCliente.html",
  "encuesta.html",
  "encuestas.html",
  "index.html",
  "modificaDispensador.html",
  "modificaUsuario.html",
  "p.html",
  "perfil.html",
  "site.webmanifest",
  "usuarios.html",
  "css/admin.css",
  "css/agregaDispensador.css",
  "css/agregaUsuario.css",
  "css/cliente.css",
  "css/encuesta.css",
  "css/index.css",
  "css/login.css",
  "css/modificaDispensador.css",
  "css/modificarUsuarios.css",
  "css/nav.css",
  "css/perfil.css",
  "error/cuenblanco.html",
  "error/datosincorrectos.html",
  "error/dispensadornoencontrado.html",
  "error/errorinterno.html",
  "error/faltacue.html",
  "error/faltadescripcion.html",
  "error/faltaid.html",
  "error/faltamatch.html",
  "error/faltanombre.html",
  "error/faltaproducto.html",
  "error/idenblanco.html",
  "error/matchenblanco.html",
  "error/noautorizado.html",
  "error/nojson.html",
  "error/nombreenblanco.html",
  "error/productoenblanco.html",
  "error/resultadonojson.html",
  "error/rolincorrecto.html",
  "error/sesioniniciada.html",
  "icons/add.svg",
  "icons/correct.svg",
  "icons/dispensadorAdmin.svg",
  "icons/dispensadorCli.svg",
  "icons/edit.svg",
  "icons/editar.svg",
  "icons/encuestas.svg",
  "icons/mail.svg",
  "icons/noti.svg",
  "icons/notificacion.svg",
  "icons/pass.svg",
  "icons/perfil.svg",
  "icons/pru.svg",
  "icons/return.svg",
  "icons/trash.svg",
  "icons/usuario.svg",
  "img/desktop.jpg",
  "img/dispensador.png",
  "img/dispense.png",
  "img/logo.jpg",
  "img/logo2.png",
  "img/maskable_icon.png",
  "img/maskable_icon_x128.png",
  "img/maskable_icon_x192.png",
  "img/maskable_icon_x384.png",
  "img/maskable_icon_x48.png",
  "img/maskable_icon_x512.png",
  "img/maskable_icon_x72.png",
  "img/maskable_icon_x96.png",
  "img/mobile.jpg",
  "img/techDispense.png",
  "img/user.jpg",
  "js/CUE.js",
  "js/menu.js",
  "js/protege.js",
  "js/registraServiceWorker.js",
  "js/ROL_IDS.js",
  "js/ROL_ID_ADMINISTRADOR.js",
  "js/ROL_ID_CLIENTE.js",
  "js/Sesion.js",
  "js/custom/mi-nav.js",
  "lib/js/consumeJson.js",
  "lib/js/exportaAHtml.js",
  "lib/js/htmlentities.js",
  "lib/js/muestraError.js",
  "lib/js/muestraObjeto.js",
  "lib/js/ProblemDetails.js",
  "lib/js/submitForm.js",
  "paho.javascript-1.0.3/about.html",
  "paho.javascript-1.0.3/CONTRIBUTING.md",
  "paho.javascript-1.0.3/edl-v10",
  "paho.javascript-1.0.3/epl-v10",
  "paho.javascript-1.0.3/paho-mqtt-min.js",
  "paho.javascript-1.0.3/paho-mqtt.js",
  "/"
]
// Verifica si el código corre dentro de un service worker.
if (self instanceof ServiceWorkerGlobalScope) {
  // Evento al empezar a instalar el servide worker,
  self.addEventListener("install",
    (/** @type {ExtendableEvent} */ evt) => {
      console.log("El service worker se está instalando.")
      evt.waitUntil(llenaElCache())
    })
  // Evento al solicitar información a la red.
  self.addEventListener("fetch", (/** @type {FetchEvent} */ evt) => {
    if (evt.request.method === "GET") {
      evt.respondWith(buscaLaRespuestaEnElCache(evt))
    }
  })
  // Evento cuando el service worker se vuelve activo.
  self.addEventListener("activate",
    () => console.log("El service worker está activo."))
}
async function llenaElCache() {
  console.log("Intentando cargar caché:", CACHE)
  // Borra todos los cachés.
  const keys = await caches.keys()
  for (const key of keys) {
    await caches.delete(key)
  }
  // Abre el caché de este service worker.
  const cache = await caches.open(CACHE)
  // Carga el listado de ARCHIVOS.
  await cache.addAll(ARCHIVOS)
  console.log("Cache cargado:", CACHE)
  console.log("Versión:", VERSION)
}
/** @param {FetchEvent} evt */
async function buscaLaRespuestaEnElCache(evt) {
  // Abre el caché.
  const cache = await caches.open(CACHE)
  const request = evt.request
  /* Busca la respuesta a la solicitud en el contenido del caché, sin
   * tomar en cuenta la parte después del símbolo "?" en la URL. */
  const response = await cache.match(request, { ignoreSearch: true })
  if (response === undefined) {
    /* Si no la encuentra, empieza a descargar de la red y devuelve
     * la promesa. */
    return fetch(request)
  } else {
    // Si la encuentra, devuelve la respuesta encontrada en el caché.
    return response
  }
}

