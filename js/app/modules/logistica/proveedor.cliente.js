window.operateEvents = {
    'click .btn-modal-detail': function (e, value, row, index) {
        modalShow('Detalles Proveedor / Cliente', '/logistica/proveedor-cliente/' + row.id + '/show', 'modal-lg')
    },
    'click .btn-modal-edit': function (e, value, row, index) {
        modalShow('Modificar Proveedor / Cliente', '/logistica/proveedor-cliente/' + row.id + '/edit', 'modal-xl')
    }
}

function operateFormatter(value, row, index) {
    let estado = row.isModificable ? ' btn-modal-edit" ' : 'disabled"';

    let btnDetalle = [
        '<button class="btn btn-secondary btn-modal-detail" title="Detalles" data-toggle="tooltip" data-placement="top" title="Detalle">',
        '<i class="fas fa-eye fa-sm"></i>',
        '</button>'
    ].join('');

    let btnEdit = [
        `<button class="btn btn-secondary ${estado} title="Editar" data-toggle="tooltip" data-placement="top" title="Editar">`,
        `<i class="fas fa-pencil-alt fa-sm"></i>`,
        `</button>`,
    ].join('')

    return [
        '<div class="btn-group btn-group-sm" role="group" aria-label="Acciones">',
        btnDetalle,
        btnEdit,
        '</div>'
    ].join('');
}

let $table = $('#table');

$table.bootstrapTable({
    loadingFontSize: '16px'
});

$('#modal').on('hidden.bs.modal', function (e) {
    $table.bootstrapTable('refresh');
});
