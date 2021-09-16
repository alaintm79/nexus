"use strict";

/*! usuario.js
 * ================
 *
 * @Author Alain TM
 * @Email  <alaintm79@gmail.com>
 * @Update 21062201
 */

(function () {

    /*** Vars ***/

    /*** Methods & Functions ***/
    window.operateFormatterByAdmin = function (value, row, index) {
        return [
            `<div class="btn-group btn-group-sm" role="group" aria-label="Acciones">`,
            `<button class="btn btn-secondary btn-modal-edit" title="Modificar" data-toggle="tooltip" data-placement="top">`,
            `<i class="fas fa-pencil-alt fa-sm"></i>`,
            `</button>`,
            `</div>`
        ].join('');
    }

    window.operateFormatterByGestor = function (value, row, index) {

        let hasAccount = row.hasAccount === true ? ' btn-modal-password" ' : 'disabled"';

        return [
            `<div class="btn-group btn-group-sm" role="group" aria-label="Acciones">`,
            `<button class="btn btn-secondary ${hasAccount}" title="Modificar" data-toggle="tooltip" data-placement="top">`,
            `<i class="fas fa-pencil-alt fa-sm"></i>`,
            `</button>`,
            `</div>`
        ].join('')
    }

    window.isActiveFormatter = function (value, row, index) {
        if (row.usuario != '') {
            return (value) ? 'SI' : 'NO';
        }

        return ''
    }

    window.arrayFormatter = function (value, row, index) {
        let values = value;

        if (values != null) {
            let str = values.toString();
            str = str.replace(/[\[\|&;\$%@"<>\(\)\]\+]/g, "");
            str = str.replace(/[,]/g, ", ");

            return str
        }

        return ''
    }

    window.syncFormatter = function (value, row, index) {
        if (row.usuario != '' && row.isSyncPassword != null) {
            return (value) ? 'SI' : 'NO';
        }

        return ''
    }

    /*** Inits & Event Listeners ***/

    // BoostrapTable Acciones
    window.operateEvents = {
        'click .btn-modal-edit': function (e, value, row, index) {
            window.location.href = '/sistema/usuarios/' + row.id + '/edit';
        },
        'click .btn-modal-password': function (e, value, row, index) {
            window.location.href = '/sistema/usuarios/' + row.token + '/update';
        }
    };
})();
