"use strict";function operateEditFormatter(t,e,n){var o=e.estado!=["FIRMADO"]?' btn-modal-edit" ':'disabled"';return['<div class="btn-group btn-group-sm" role="group" aria-label="Acciones">',['<button class="btn btn-secondary btn-modal-detail" title="Detalles">','<i class="fas fa-eye fa-sm"></i>',"</button>"].join(""),['<button class="btn btn-secondary '.concat(o,' title="Editar">'),'<i class="fas fa-pencil-alt fa-sm"></i>',"</button>"].join(""),"</div>"].join("")}window.operateEvents={"click .btn-modal-detail":function(t,e,n){parent.document.getElementById("modalTitle").textContent="Detalle Suplemento",window.location.href="/logistica/suplemento/"+n.id+"/show"},"click .btn-modal-edit":function(t,e,n){parent.document.getElementById("modalTitle").textContent="Modificar Suplemento",window.location.href="/logistica/suplemento/"+n.id+"/edit"}};var $table=$("#table");$table.bootstrapTable({loadingFontSize:"16px"});