"use strict"
const nombreDeServiceWorker = "sw.js"
try {
 navigator.serviceWorker.register(nombreDeServiceWorker)
  .then(registro => {
   console.log(nombreDeServiceWorker, "registrado.")
   console.log(registro)
  })
  .catch(error => console.log(error))
} catch (error) {
 console.log(error)
}