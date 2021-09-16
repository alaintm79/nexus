"use strict";
/*! opcion.js
 * ================
 *
 * @Author  Alain TM
 * @Email   <alaintm79@gmail.com>
 * @Update 21061301
 */

/*** Funciones de Formatos ***/

(function () {

    /*** Vars ***/
    const $table = $('#table');

    // Opciones con Filemanager
    const btnSplash = document.getElementById('btnSplash');
    const btnSplashRemove = document.getElementById('btnSplashRemove');
    const btnLogo = document.getElementById('btnLogo');
    const btnLogoRemove = document.getElementById('btnLogoRemove');
    const btnSidebar = document.getElementById('btnSidebar');
    const btnSidebarRemove = document.getElementById('btnSidebarRemove');
    const fileManager = document.getElementById("fileManager");

    /*** Methods & Functions ***/


    /*** Inits & Event Listeners ***/
    window.operateFormatter = function (value, row, index) {
        return [
            '<div class="btn-group btn-group-sm" role="group" aria-label="Acciones">',
            '<button class="btn btn-secondary btn-modal-edit" title="Modificar" data-toggle="tooltip" data-placement="top" title="Modificar">',
            '<i class="fas fa-pencil-alt fa-sm"></i>',
            '</button>',
            '</div>'
        ].join('')
    };

    // BoostrapTable Acciones
    window.operateEvents = {
        'click .btn-modal-edit': function (e, value, row, index) {
            window.location.href = '/blog/admin/opciones/opcion/' + row.id + '/edit';
        }
    };

    // Gestión de opciones con imagenes empleando filemanger
    btnSplash.addEventListener('click', event => {

        $('#modalFile').modal('show');

        fileManager.addEventListener("load", function () {
            let pathSplash = document.getElementById('pathSplash');
            let thumbSplash = document.getElementById('thumbSplash');

            setInterval(function () {
                fileManager.contentWindow.document.querySelectorAll('.select').forEach(el => el.addEventListener('click', event => {
                    pathSplash.value = event.target.getAttribute("data-path");
                    thumbSplash.style.backgroundImage = "url(' " + pathSplash.value + " ')";
                    document.getElementById('btnSplashSubmit').disabled = false;

                    $('#modalFile').modal('hide');
                }));
            }, 10);
        });
    });

    btnSplashRemove.addEventListener('click', event => {
        document.getElementById('thumbSplash').style.backgroundImage = "";
        document.getElementById('btnSplashSubmit').disabled = false;

        event.preventDefault();
    });

    btnLogo.addEventListener('click', event => {

        $('#modalFile').modal('show');

        fileManager.addEventListener("load", function () {
            let pathLogo = document.getElementById('pathLogo');
            let thumbLogo = document.getElementById('thumbLogo');

            setInterval(function () {
                fileManager.contentWindow.document.querySelectorAll('.select').forEach(el => el.addEventListener('click', event => {
                    pathLogo.value = event.target.getAttribute("data-path");
                    thumbLogo.style.backgroundImage = "url(' " + pathLogo.value + " ')";
                    document.getElementById('btnLogoSubmit').disabled = false;

                    $('#modalFile').modal('hide');
                }));
            }, 10);
        });
    });

    btnLogoRemove.addEventListener('click', event => {
        document.getElementById('thumbLogo').style.backgroundImage = "";
        document.getElementById('btnLogoSubmit').disabled = false;
    });

    btnSidebar.addEventListener('click', event => {

        $('#modalFile').modal('show');

        fileManager.addEventListener("load", function () {
            let pathSidebar = document.getElementById('pathSidebar');
            let thumbSidebar = document.getElementById('thumbSidebar');

            setInterval(function () {
                fileManager.contentWindow.document.querySelectorAll('.select').forEach(el => el.addEventListener('click', event => {
                    pathSidebar.value = event.target.getAttribute("data-path");
                    thumbSidebar.style.backgroundImage = "url(' " + pathSidebar.value + " ')";
                    document.getElementById('btnSidebarSubmit').disabled = false;

                    $('#modalFile').modal('hide');
                }));
            }, 10);
        });
    });

    btnSidebarRemove.addEventListener('click', event => {
        document.getElementById('thumbSidebar').style.backgroundImage = "";
        document.getElementById('btnSidebarSubmit').disabled = false;
    });
})();
