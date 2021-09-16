"use strict";

/*! contact.form.js
 * ================
 *
 * @Author Alain TM
 * @Email  <alaintm79@gmail.com>
 * @Update 21062201
 */

(function () {

    /*** Vars ***/
    const content = document.getElementById('content');

    // Validación si ha cambiado la información en el formulario

    /*** Methods & Functions ***/

    /*** Inits & Event Listeners ***/
    Inputmask().mask(document.querySelectorAll("input"));

    let ext = document.getElementById("extension");
    let telTrabajo = document.getElementById("telefonoFijoTrabajo");

    document.querySelectorAll('.btn-submit').forEach(item => {
        item.addEventListener('click', (e) => {
            if (ext.value != '') {
                telTrabajo.setAttribute('required', 'required')
            } else {
                telTrabajo.removeAttribute('required')
            }
        }, false);
    });

    // Asignación de CUBA como país por defecto.
    if (content.dataset.action == 'new') {
        $('#pais').selectpicker('val', '1');
    }

})();
