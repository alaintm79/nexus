/*! assets/js/app.js
 * ================
 *
 * @Author  Alain TM
 * @Email   <alaintm79@gmail.com>
 * @version 0.3
 */
import '../../bootstrap'

// Import CSS
import '@fortawesome/fontawesome-free/css/all.min.css';
import 'admin-lte/dist/css/adminlte.min.css';
import 'overlayscrollbars/css/OverlayScrollbars.min.css';
import 'bootstrap-table/dist/bootstrap-table.min.css';
import 'bootstrap-table/dist/extensions/filter-control/bootstrap-table-filter-control.min.css';
import 'bootstrap-table/dist/extensions/fixed-columns/bootstrap-table-fixed-columns.min.css';
import 'bootstrap-select/dist/css/bootstrap-select.min.css';
import '../../css/common/fonts.css';
import '../../css/common/svg-icons-animate.css';
import '../../css/app/app.scss';

// Import JS Modules

const $ = require('jquery');

// Create global $ and jQuery variables
global.$ = global.jQuery = $;

// start the Stimulus application
// import './bootstrap';

import 'overlayscrollbars';
import 'admin-lte';
import 'bootstrap/js/dist/modal';
import 'bootstrap/js/dist/dropdown';
import 'bootstrap/js/dist/toast';
import 'tableexport.jquery.plugin';
import 'bootstrap-table';
import 'bootstrap-table/dist/extensions/filter-control/bootstrap-table-filter-control';
import 'bootstrap-table/dist/extensions/fixed-columns/bootstrap-table-fixed-columns';
import 'bootstrap-table/dist/extensions/export/bootstrap-table-export';
import 'bootstrap-table/dist/extensions/cookie/bootstrap-table-cookie';
import 'bootstrap-select';
import 'bootstrap-select/js/i18n/defaults-es_ES';
import 'Hinclude/hinclude';
import swal from 'sweetalert';
import Inputmask from "inputmask";
import '../locale/bootstrap-table-es-ES.min';


/*** Global Functions ***/

// Formato para valores booleanos
window.boolFormatter = function (value, row, index) {
    return (value) ? 'SI' : 'NO';
}

// Validación fomularios
function formValidation() {
    let forms = document.getElementsByClassName('needs-validation');

    if (forms.length > 0) { // Validación si existe en el DOM

        // Bucle para recorrer los formularios para aplicar estilo y evitar el envio
        Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add('was-validated');
            }, false);
        });
    }
}

// Asignar atributos src y heigh al iframe y mostrar el la ventana modal
// function modalShow(title, url, size, height, scroll) {

(function () {

    // Vars
    const btnScrollToTop = document.querySelector(".btn-scroll-to-top");
    const rootElement = document.documentElement;
    const TOGGLE_RATIO = 0.05;

    // Obtener los botones con clase .btn-modal a los que aplicar evento click para activar ventana modal
    let btnModals = document.getElementsByClassName("btn-modal");

    // Methods & Functions
    function handleScroll() { // do something on scroll
        let scrollTotal = rootElement.scrollHeight - rootElement.clientHeight
        if ((rootElement.scrollTop / scrollTotal) > TOGGLE_RATIO) { // show button
            btnScrollToTop.classList.add("btn-show");
        } else { // hide button
            btnScrollToTop.classList.remove("btn-show");
        }
    }

    function scrollToTop() { // scroll to top logic
        rootElement.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    }

    // Inits & Event Listeners

    if (btnScrollToTop) {
        btnScrollToTop.addEventListener("click", scrollToTop);
        document.addEventListener("scroll", handleScroll);
    }

    formValidation();

    setTimeout(function () {
        const loader = document.getElementById('loader');

        if (loader) {
            loader.style.opacity = 0;
            loader.style.height = 0;

            setTimeout(function () {
                loader.style.visibility = "none";
            }, 200);
        }
    }, 500);
})();

