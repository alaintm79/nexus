"use strict";

/*! directorio.js
 * ================
 *
 * @Author Alain TM
 * @Email  <alaintm79@gmail.com>
 * @Update 21062001
 */

import swal from 'sweetalert';
import Inputmask from "inputmask";

(function () {

    // Vars
    const form = document.getElementById('form');
    const action = form.dataset.action;
    const directorioId = form.dataset.directorioId;
    const directorioNombre = form.dataset.directorioNombre;

    // Methods & Functions

    // Inits & Event Listeners
    Inputmask().mask(document.querySelectorAll("input"));

    if (action === 'edit') {
        const btnDelete = document.getElementById('btnDelete');

        btnDelete.addEventListener('click', (event) => {
            event.preventDefault();
            swal({
                text: "Esta seguro de eliminar el directorio " + directorioNombre + "!",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    window.location.href = '/blog/admin/directorios/' + directorioId + '/delete';
                }
            });
        }, true);
    }
})();
