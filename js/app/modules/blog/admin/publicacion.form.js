"use strict";

/*! publicacion.js
 * ================
 *
 * @Author Alain TM
 * @Email  <alaintm79@gmail.com>
 * @Update 21062001
 */

(function () {

    // Vars
    const ckeContenido = CKEDITOR.instances['publicacion_contenido'];
    const contenido = document.getElementById('publicacion_contenido');
    const thumb = document.getElementById('publicacion_thumbnail');
    const btnAddThumb = document.getElementById('btnAddThumb');
    const btnRemoveThumb = document.getElementById('btnRemoveThumb');
    const fileManager = document.getElementById("fileManager");
    const thumbPreview = document.getElementById('thumbPreview');
    const action = form.dataset.action;

    // Methods & Functions

    // Inits & Event Listeners

    ckeContenido.on('contentDom', function () {
        this.document.on('click', function () {
            contenido.classList.add('is-focus');

            if (contenido.classList.contains('is-validated') && !contenido.classList.contains('is-invalid')) {
                contenido.classList.remove('is-focus');
            }

            if (contenido.classList.contains('is-validated') && contenido.classList.contains('is-invalid')) {
                contenido.classList.remove('is-focus');
            }
        });

        this.on('blur', function () {
            contenido.classList.remove('is-focus');
        });

        this.on('change', function () {
            if (ckeContenido.getData().replace(/<[^>]*>/gi, '').length > 0 && contenido.classList.contains('is-invalid')) {
                contenido.classList.remove('is-invalid');
            }

            if (ckeContenido.getData().replace(/<[^>]*>/gi, '').length == 0 && contenido.classList.contains('is-validated')) {
                contenido.classList.add('is-invalid');
            }
        });
    });

    document.getElementById('btnSubmit').addEventListener('click', event => {

        contenido.classList.add('is-validated');

        if (ckeContenido.getData().replace(/<[^>]*>/gi, '').length == 0) {
            contenido.classList.add('is-invalid');
            formValidation(event);
        } else {
            contenido.classList.remove('is-invalid');
        }
    }, false);

    btnAddThumb.addEventListener('click', () => {

        $('#modalFile').modal('show');

        fileManager.addEventListener("load", () => {
            setInterval(function () {
                fileManager.contentWindow.document.querySelectorAll('.select').forEach(el => el.addEventListener('click', event => {
                    thumb.value = event.target.getAttribute("data-path");
                    thumbPreview.style.backgroundImage = 'url(' + thumb.value + ')';
                    $('#modalFile').modal('hide');
                }));
            }, 10);
        });

    });

    btnRemoveThumb.addEventListener('click', event => {
        thumb.value = '';
        thumbPreview.style.backgroundImage = "url('')";
    });

    if(action === 'new'){
        let date = document.getElementById('publicacion_fechaPublicacion_date');
        let time = document.getElementById('publicacion_fechaPublicacion_time');
        let datetimeStatus = document.getElementById('datetime_status');
        let dateInit = date.value;
        let timeInit = time.value;

        date.addEventListener('input', () => {
            (date.value !== dateInit || time.value !== timeInit) ? datetimeStatus.value = 'modified' : datetimeStatus.value = 'default';
        });

        time.addEventListener('input', () => {
            (time.value !== timeInit || date.value !== dateInit) ? datetimeStatus.value = 'modified' : datetimeStatus.value = 'default';
        });
    }
})();
