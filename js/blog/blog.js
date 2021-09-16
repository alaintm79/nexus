/*! assets/js/blog/blog.js
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
import '../../css/common/fonts.css';
import '../../css/common/svg-icons-animate.css';
import '../../css/blog/blog.scss';

// Import JS Modules

const $ = require('jquery');

// create global $ and jQuery variables
global.$ = global.jQuery = $;

import 'overlayscrollbars';
import 'admin-lte';
import 'bootstrap-table';
import '../locale/bootstrap-table-es-ES.min';
import 'Hinclude/hinclude';

(function () {

    // Vars
    const btnScrollToTop = document.querySelector(".btn-scroll-to-top");
    const rootElement = document.documentElement;
    const TOGGLE_RATIO = 0.05;

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

	// Inits & Event Listeners
    btnScrollToTop.addEventListener("click", scrollToTop);
    document.addEventListener("scroll", handleScroll);

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
