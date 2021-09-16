"use strict";
/*! contacto.js
 * ================
 *
 * @Author Alain TM
 * @Email  <alaintm79@gmail.com>
 * @Update 21062201
 */

import swal from 'sweetalert';

(function () {

    /*** Vars ***/

    /*** Methods & Functions ***/
    window.operateFormatterByAdmin = function (value, row, index) {
        return [
            '<div class="btn-group btn-group-sm" role="group" aria-label="Acciones">',
            '<button class="btn btn-secondary btn-modal-detail" title="Detalles" data-toggle="tooltip" data-placement="top" title="Detalle">',
            '<i class="fas fa-eye fa-sm"></i>',
            '</button>',
            '<button class="btn btn-secondary btn-modal-edit" title="Editar" data-toggle="tooltip" data-placement="top" title="Editar">',
            '<i class="fas fa-pencil-alt fa-sm"></i>',
            '</button>',
            '<button class="btn btn-secondary btn-modal-delete" title="Editar" data-toggle="tooltip" data-placement="top" title="Editar">',
            '<i class="fas fa-trash-alt fa-sm"></i>',
            '</button>',
            '</div>'
        ].join('')
    }

    window.operateFormatterByGestor = function (value, row, index) {
        return [
            '<div class="btn-group btn-group-sm" role="group" aria-label="Acciones">',
            '<button class="btn btn-secondary btn-modal-detail" title="Detalles" data-toggle="tooltip" data-placement="top" title="Detalle">',
            '<i class="fas fa-eye fa-sm"></i>',
            '</button>',
        ].join('')
    }

    /*** Inits & Event Listeners ***/

    // BoostrapTable Acciones
    window.operateEvents = {
        'click .btn-modal-detail': function (e, value, row, index) {
            window.location.href = '/contactos/' + row.id + '/show';
        },
        'click .btn-modal-edit': function (e, value, row, index) {
            window.location.href = '/contactos/' + row.id + '/edit';
        },
        'click .btn-modal-delete': function (e, value, row, index) {
            swal({
                text: "Esta seguro de eliminar el contacto " + row.nombre + ' ' + row.apellidos,
                buttons: true,
                dangerMode: true,
                width: '800px'
            })
            .then((willDelete) => {
                if (willDelete) {
                    window.location.href = '/contactos/' + row.id + '/delete';
                }
            });
        }
    }

})();
