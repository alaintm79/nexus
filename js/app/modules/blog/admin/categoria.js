/*! categoria.js
 * ================
 *
 * @Author  Alain TM
 * @Email   <alaintm79@gmail.com>
 * @Update 21062001
 */

(function () {

    /*** Vars ***/

    /*** Methods & Functions ***/
    window.operateFormatter = function (value, row, index) {
        return [
            '<div class="btn-group btn-group-sm" role="group" aria-label="Acciones">',
            '<button class="btn btn-secondary btn-modal-edit" title="Modificar" data-toggle="tooltip" data-placement="top" title="Modificar">',
            '<i class="fas fa-pencil-alt fa-sm"></i>',
            '</button>',
            '</div>'
        ].join('')
    }

    /*** Inits & Event Listeners ***/

    // BoostrapTable Acciones
    window.operateEvents = {
        'click .btn-modal-edit': function (e, value, row, index) {
            window.location.href = '/blog/admin/categorias/' + row.id + '/edit';
        }
    };
})();



