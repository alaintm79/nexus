
// Acciones
window.operateEvents = {
    'click .btn-modal-detail': function (e, value, row, index) {
        modalShow('Detalles Contacto', '/contactos/' + row.id + '/show', 'modal-xl', '674')
    },
    'click .btn-modal-edit': function (e, value, row, index) {
        modalShow('Modificar Contacto', '/contactos/' + row.id + '/edit', 'modal-xl', '734')
    },
    'click .btn-modal-delete': function (e, value, row, index) {
        modalShow('Eliminar Contacto', '/contactos/' + row.id + '/delete', 'modal-lg')
    }
}

function operateFormatterByAdmin(value, row, index) {
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

function operateFormatterByGestor(value, row, index) {
    return [
        '<div class="btn-group btn-group-sm" role="group" aria-label="Acciones">',
        '<button class="btn btn-secondary btn-modal-detail" title="Detalles" data-toggle="tooltip" data-placement="top" title="Detalle">',
        '<i class="fas fa-eye fa-sm"></i>',
        '</button>',
    ].join('')
}

let $table = $('#table');

$table.bootstrapTable({
    loadingFontSize: '16px'
});

$('#modal').on('hidden.bs.modal', function (e) {
    $table.bootstrapTable('refresh');
});
