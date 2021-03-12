// Acciones
window.operateEvents = {
    'click .btn-modal-edit': function (e, value, row, index) {
        modalShow('Modificar Usuario', '/sistema/usuarios/' + row.id + '/edit', 'modal-lg', '713')
    },
    'click .btn-modal-password': function (e, value, row, index) {
        modalShow('Modificar Clave', '/sistema/usuarios/' + row.id + '/password', 'modal-lg', '180')
    }
};

function operateFormatterByAdmin(value, row, index) {
    return [
        `<div class="btn-group btn-group-sm" role="group" aria-label="Acciones">`,
        `<button class="btn btn-secondary btn-modal-edit" title="Modificar" data-toggle="tooltip" data-placement="top">`,
        `<i class="fas fa-pencil-alt fa-sm"></i>`,
        `</button>`,
        `</div>`
    ].join('')
}

function operateFormatterByGestor(value, row, index) {
    let hasAccount = row.hasAccount === true? ' btn-modal-password" ' : 'disabled"';
    return [
        `<div class="btn-group btn-group-sm" role="group" aria-label="Acciones">`,
        `<button class="btn btn-secondary ${hasAccount}" title="Modificar" data-toggle="tooltip" data-placement="top">`,
        `<i class="fas fa-pencil-alt fa-sm"></i>`,
        `</button>`,
        `</div>`
    ].join('')
}

// Formatos
function isActiveFormatter(value, row, index){
    if(row.usuario != ''){
        return (value) ? 'SI' : 'NO';
    }

    return ''
}

function rolFormatter(value, row){
    let rol = value

    if(value != null){
        rol = value.replace(/[\[\|&;\$%@"<>\(\)\]\+]/g, "");
        rol = rol.replace(/[,]/g, ", ");

        return rol
    }

    return ''
}

function syncFormatter(value, row, index){
    if(row.usuario != '' && row.isSyncPassword != null){
        return (value) ? 'SI' : 'NO';
    }

    return ''
}

let $table = $('#table');

$table.bootstrapTable({
    loadingFontSize: '16px'
});

$('#modal').on('hidden.bs.modal', function (e) {
    $table.bootstrapTable('refresh');
});
