import { htmlentities } from "../../lib/js/htmlentities.js"
import { Sesion } from "../Sesion.js"
import { ROL_ID_ADMINISTRADOR } from "../ROL_ID_ADMINISTRADOR.js"
import { ROL_ID_CLIENTE } from "../ROL_ID_CLIENTE.js"

export class MiNav extends HTMLElement {

  connectedCallback() {
    this.style.display = "block";

    this.innerHTML = /* html */ `
      <style>
        nav {
          margin: 0;
          padding: 0;
          box-sizing: border-box;
          width: 100%;
          background-color: #000000;
          color: #ffffff;
          padding: 0.75rem 1rem;
          position: relative;
          font-weight: 500;
        }

        nav ul {
          list-style: none;
          margin: 0;
          padding: 0;
          display: flex;
          gap: 1rem;
          align-items: center;
          transition: max-height 0.3s ease;
          overflow: hidden;
        }

        nav li {
          margin: 0;
        }

        nav a {
          color: #ffffff;
          text-decoration: none;
          padding: 0.5rem 1rem;
          border-radius: 6px;
          display: block;
          transition: background-color 0.3s ease;
        }

        nav a:hover {
          background-color: #222222;
        }

        /* Botón hamburguesa: oculto en desktop */
        .hamburger {
          display: none;
          flex-direction: column;
          justify-content: space-around;
          width: 25px;
          height: 20px;
          cursor: pointer;
        }

        .hamburger div {
          width: 25px;
          height: 3px;
          background-color: #ffffff;
          border-radius: 2px;
          transition: all 0.3s ease;
        }

        nav.open ul {
          max-height: 500px; /* suficiente para mostrar todos los enlaces */
        }

        @media (max-width: 768px) {
          nav ul {
            flex-direction: column;
            max-height: 0;
            width: 100%;
            background-color: #000000;
          }

          nav.open ul {
            max-height: 500px;
          }

          .hamburger {
            display: flex;
          }

          nav ul li {
            width: 100%;
            text-align: center;
          }

          nav a {
            padding: 1rem;
            border-radius: 0;
            border-bottom: 1px solid #222222;
          }
        }
      </style>

      <nav>
        <div class="hamburger" aria-label="Menú" role="button" tabindex="0">
          <div></div>
          <div></div>
          <div></div>
        </div>
        <ul>
          <li><a href="#">Cargando&hellip;</a></li>
        </ul>
      </nav>
    `;

    const nav = this.querySelector("nav");
    const hamburger = this.querySelector(".hamburger");

    if (hamburger && nav) {
      hamburger.addEventListener("click", () => {
        nav.classList.toggle("open");
      });

      hamburger.addEventListener("keydown", (e) => {
        if (e.key === "Enter" || e.key === " ") {
          e.preventDefault();
          nav.classList.toggle("open");
        }
      });
    } else {
      console.warn("No se encontró el botón hamburguesa o el nav");
    }

  }



  /**
   * @param {Sesion} sesion
   */
  set sesion(sesion) {
  const cue = sesion.cue
  const rolIds = sesion.rolIds
  let innerHTML = this.hipervinculosAdmin(rolIds)
  innerHTML += this.hipervinculosCliente(rolIds)
  innerHTML += /* html */ `<li><a href="perfil.html">Perfil</a></li>`
  const ul = this.querySelector("ul")
  if (ul !== null) {
   ul.innerHTML = innerHTML
  }
 }

  /**
   * @param {Set<string>} rolIds
   */
  hipervinculosAdmin(rolIds) {
    return rolIds.has(ROL_ID_ADMINISTRADOR) ?
   /* html */ `<li><a href="admin.html">Para administradores</a></li>`
      : ""
  }

  /**
   * @param {Set<string>} rolIds
   */
  hipervinculosCliente(rolIds) {
    return rolIds.has(ROL_ID_CLIENTE) ?
   /* html */ `<li><a href="cliente.html">Para clientes</a></li>`
      : ""
  }
}

customElements.define("mi-nav", MiNav)