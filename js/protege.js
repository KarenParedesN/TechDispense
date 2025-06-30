import { consumeJson } from "../lib/js/consumeJson.js"
import { exportaAHtml } from "../lib/js/exportaAHtml.js"
import { muestraError } from "../lib/js/muestraError.js"
import { MiNav } from "./custom/mi-nav.js"
import { Sesion } from "./Sesion.js"

const SERVICIO = 'srv/sesion-actual.php'
const URL_DE_PROTECCION = 'index.html'

/**
 * @param {MiNav} [nav]
 * @param {string[]} [rolIdsPermitidos]
 */
export async function protege(nav, rolIdsPermitidos) {
 try {

  const respuesta = await consumeJson(SERVICIO)
  const sesion = new Sesion(respuesta.body)

  if (nav) {
   nav.sesion = sesion
  }

  if (rolIdsPermitidos === undefined) {

   return sesion

  } else {

   const rolIds = sesion.rolIds

   for (const rolId of rolIdsPermitidos) {
    if (rolIds.has(rolId)) {
     return sesion
    }
   }

   if (location.href !== URL_DE_PROTECCION) {
    location.href = URL_DE_PROTECCION
   }

   throw new Error("No autorizado.")

  }

 } catch (error) {
  muestraError(error)
 }
}

exportaAHtml(protege)