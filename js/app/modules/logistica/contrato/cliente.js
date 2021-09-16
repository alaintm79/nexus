window.operateEvents = {
    'click .btn-modal-detail': function (e, value, row, index) {
        modalShow('Detalle Contrato', '/logistica/contrato/cliente/' + row.id + '/show', 'modal-xl', '491')
    },
    'click .btn-modal-edit': function (e, value, row, index) {
        modalShow('Modificar Contrato', '/logistica/contrato/cliente/' + row.id + '/edit', 'modal-xl', '584')
    },
    'click .btn-modal-suplemento': function (e, value, row, index) {
        modalShow('Suplementos Contrato ' + row.numero.toUpperCase(), '/logistica/contrato/suplemento/' + row.id, 'modal-xl', '188')
    }
}

let $table = $('#table');

$table.bootstrapTable({
    loadingFontSize: '16px'
});

$('#modal').on('hidden.bs.modal', function (e) {
    $table.bootstrapTable('refresh');
});
