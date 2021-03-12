window.operateEvents = {
    'click .btn-modal-detail': function (e, value, row, index) {
        let url = row.tipo === 'p' ? '/logistica/contrato/proveedor/' + row.id + '/show' : '/logistica/contrato/cliente/' + row.id + '/show';

        modalShow('Detalle Contratos', url, 'modal-xl', '470')
    },
}

let $table = $('#table');

$table.bootstrapTable({
    loadingFontSize: '16px'
});

$('#modal').on('hidden.bs.modal', function (e) {
    $table.bootstrapTable('refresh');
});
