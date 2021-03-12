function operateEditFormatter(value, row, index) {
    let estado = row.estado != ['FIRMADO'] ? ' btn-modal-edit" ' : 'disabled"';
    let suplemento = row.numero !== null &&  row.estado == ['FIRMADO'] ? ' btn-modal-suplemento"' : '" disabled';
    let ejecucion = row.numero !== null &&  row.estado == ['FIRMADO'] ? ' btn-modal-ejecucion"' : '" disabled';

    let btnDetalle = [
        '<button class="btn btn-secondary btn-modal-detail" title="Detalles">',
        '<i class="fas fa-eye fa-sm"></i>',
        '</button>'
    ].join('');

    let btnSuplementos = [
        `<button class="btn btn-secondary ${suplemento} title="Suplementos">`,
        `<i class="fas fa-file-alt fa-sm"></i>`,
        `</button>`
    ].join('');

    let btnEjecuciones = [
        `<button class="btn btn-secondary ${ejecucion} title="Ejecuciones">`,
        `<i class="fas fa-clipboard-check fa-sm"></i>`,
        `</button>`
    ].join('');

    let btnEdit = [
        `<button class="btn btn-secondary ${estado} title="Editar">`,
        `<i class="fas fa-pencil-alt fa-sm"></i>`,
        `</button>`,
    ].join('')

    return [
        '<div class="btn-group btn-group-sm" role="group" aria-label="Acciones">',
        btnDetalle,
        btnSuplementos,
        row.tipo == 'p' ? btnEjecuciones : '',
        btnEdit,
        '</div>'
    ].join('');
}

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
        row.tipo == 'p' ? btnEjecuciones : '',
        '</div>'
    ].join('');
}
