function operateEditFormatter(value, row, index) {
    let estado = row.estado != ['FIRMADO'] ? ' btn-modal-edit" ' : 'disabled"';

    let btnDetalle = [
        '<button class="btn btn-secondary btn-modal-detail" title="Detalles">',
        '<i class="fas fa-eye fa-sm"></i>',
        '</button>'
    ].join('');

    let btnEdit = [
        `<button class="btn btn-secondary ${estado} title="Editar">`,
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

window.operateEvents = {
    'click .btn-modal-detail': function (e, value, row, index) {
        let title = parent.document.getElementById('modalTitle');
        title.textContent = 'Detalle Suplemento';
        window.location.href = '/logistica/suplemento/' + row.id + '/show';
    },
    'click .btn-modal-edit': function (e, value, row, index) {
        // modalShow('Modificar Suplemento', '/logistica/suplemento/' + row.id + '/edit', 'modal-lg')
        let title = parent.document.getElementById('modalTitle');
        title.textContent = 'Modificar Suplemento';
        window.location.href = '/logistica/suplemento/' + row.id + '/edit';
    }
}

let $table = $('#table');

$table.bootstrapTable({
    loadingFontSize: '16px'
});
