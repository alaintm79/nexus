"use strict";

/*! modulo.js
 * ================
 *
 * @Author Alain TM
 * @Email  <alaintm79@gmail.com>
 * @Update 21062001
 */

/*** Funciones de Formatos ***/

// Formato para valores booleanos
function boolFormatter(value, row, index) {
    return (value) ? 'ACTIVO' : 'INACTIVO';
}

(function () {

    /*** Vars ***/
    const $table = $('#table');

    /*** Methods & Functions ***/

    /*** Inits & Event Listeners ***/

    // BoostrapTable Acciones
    window.operateEvents = {
        'click .btn-modal-edit': function (e, value, row, index) {
            modalShow('Modificar Módulo', '/blog/admin/modulos/' + row.id + '/edit', 'modal-lg')
        }
    };

    // BoostrapTable Init
    $table.bootstrapTable({
        loadingFontSize: '16px'
    });

    // Boostrap Modal
    $('#modal').on('hidden.bs.modal', function (e) {
        $table.bootstrapTable('refresh');
    });

})();
