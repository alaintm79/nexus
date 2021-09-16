"use strict";

/*! directorio.js
 * ================
 *
 * @Author Alain TM
 * @Email  <alaintm79@gmail.com>
 * @Update 21062201
 */

(function () {

    /*** Vars ***/
    const $table = $('#table');

    /*** Methods & Functions ***/
    window.operateFormatter = function (value, row, index) {
        return [
            '<div class="btn-group btn-group-sm" role="group" aria-label="Acciones">',
            '<button class="btn btn-secondary btn-modal-detail" title="Archivos" data-toggle="modal" data-target="#modal" title="Archivos">',
            '<i class="fa fa-copy fa-sm"></i>',
            '</button>',
            '<button class="btn btn-secondary btn-modal-edit" title="Editar" data-toggle="tooltip" data-placement="top" title="Editar">',
            '<i class="fas fa-pencil-alt fa-sm"></i>',
            '</button>',
            '</div>'
        ].join('')
    }

    /*** Inits & Event Listeners ***/
    window.operateEvents = {
        'click .btn-modal-edit': function (e, value, row, index) {
            window.location.href = '/blog/admin/archivos/' + row.id + '/edit';
        },
        'click .btn-modal-detail': function (e, value, row, index) {
            document.getElementById('iframe').setAttribute('src', '/files/?conf=directorio&route=/' + row.ruta)
        },
    };
})();
