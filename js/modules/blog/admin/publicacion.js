// const = if(content.dataset.action == 'create'){
//     $('#pais').selectpicker('val', '53');
// }
const estado = document.getElementById('table').dataset.estado;
const url  = estado != 'eliminado' ? '/trash' : '/delete';

// Acciones
window.operateEvents = {
    'click .btn-edit': function (e, value, row, index) {
        window.location.href = '/blog/admin/publicaciones/' + row.id + '/edit';
    },
    'click .btn-delete': function (e, value, row, index) {
        modalShow('Eliminar Publicación', '/blog/admin/publicaciones/' + row.id + url, 'modal-lg')
    }
}

function operateFormatter(value, row, index) {
    return [
        '<div class="btn-group btn-group-sm" role="group" aria-label="Acciones">',
        '<button class="btn btn-secondary btn-edit" title="Editar" data-toggle="tooltip" data-placement="top" title="Editar">',
        '<i class="fas fa-pencil-alt fa-sm"></i>',
        '</button>',
        '<button class="btn btn-secondary btn-delete" title="Editar" data-toggle="tooltip" data-placement="top" title="Editar">',
        '<i class="fas fa-trash-alt fa-sm"></i>',
        '</button>',
        '</div>'
    ].join('')
}

const $table = $('#table');

$table.bootstrapTable({
    loadingFontSize: '16px'
});

$('#modal').on('hidden.bs.modal', function (e) {
    // $table.bootstrapTable('refresh');
    window.location.reload(true)
});
