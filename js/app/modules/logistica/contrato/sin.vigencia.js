function operateFormatter(value, row, index) {
    let suplemento = row.numero !== null &&  row.estado == ['FIRMADO'] ? ' btn-modal-suplemento"' : '" disabled';
    let ejecucion = row.numero !== null &&  row.estado == ['FIRMADO'] ? ' btn-modal-ejecucion"' : '" disabled';

    let btnDetalle = [
        '<button class="btn btn-secondary btn-modal-detail" title="Detalles">',
        '<i class="fas fa-eye fa-sm"></i>',
        '</button>'
    ].join('');

    let btnSuplementos = [
        `<button class="btn btn-secondary ${suplemento} title="Suplementos">`,
        '<i class="fas fa-file-alt fa-sm"></i>',
        '</button>'
    ].join('');

    let btnEjecuciones = [
        `<button class="btn btn-secondary ${ejecucion} title="Ejecuciones">`,
        `<i class="fas fa-clipboard-check fa-sm"></i>`,
        `</button>`
    ].join('');

    return [
        '<div class="btn-group btn-group-sm" role="group" aria-label="Acciones">',
        btnDetalle,
        btnSuplementos,
        row.tipo === 'p' ? btnEjecuciones : ' ',
        '</div>'
    ].join('');
}

window.operateEvents = {
    'click .btn-modal-detail': function (e, value, row, index) {
        let url = row.tipo === 'p' ? '/logistica/contrato/proveedor/' + row.id + '/show' : '/logistica/contrato/cliente/' + row.id + '/show';

        modalShow('Detalle Contratos', url, 'modal-xl', '470')
    },
    'click .btn-modal-suplemento': function (e, value, row, index) {
        modalShow('Suplementos del Contrato ' + row.numero.toUpperCase(), '/logistica/contratos/suplementos/' + row.id, 'modal-xl', '731')
    }
}

let $table = $('#table');

$table.bootstrapTable({
    loadingFontSize: '16px'
});

$('#modal').on('hidden.bs.modal', function (e) {
    $table.bootstrapTable('refresh');
});
