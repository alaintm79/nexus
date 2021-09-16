"use strict";
/*! publicacion.js
 * ================
 *
 * @Author Alain TM
 * @Email  <alaintm79@gmail.com>
 * @Update 21062001
 */

(function () {

    /*** Vars ***/

    /*** Methods & Functions ***/
    window.operateFormatter = function (value, row, index) {
        return [
            '<div class="btn-group btn-group-sm" role="group" aria-label="Acciones">',
            '<button class="btn btn-secondary btn-modal-detail" title="Detalles" data-toggle="tooltip" data-placement="top" title="Detalle">',
            '<i class="fas fa-eye fa-sm"></i>',
            '</button>',
        ].join('')
    }

    window.mensajeFormatter = function (value, row, index) {
        return value.substring(0,250) + '...';
    }

    /*** Inits & Event Listeners ***/

    // BoostrapTable Acciones
    window.operateEvents = {
        'click .btn-modal-detail': function (e, value, row, index) {
            window.location.href = '/buzon/mensaje/' + row.id + '/show';
        },
        // 'click .btn-modal-delete': function (e, value, row, index) {
        //     swal({
        //         text: "Esta seguro de eliminar el contacto " + row.nombre + ' ' + row.apellidos,
        //         buttons: true,
        //         dangerMode: true,
        //         width: '800px'
        //     })
        //     .then((willDelete) => {
        //         if (willDelete) {
        //             window.location.href = '/contactos/' + row.id + '/delete';
        //         }
        //     });
        // }
    }
})();
