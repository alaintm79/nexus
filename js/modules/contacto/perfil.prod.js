"use strict";window.operateEvents={"click .btn-modal-edit":function(o,t,a){modalShow("Modificar Contacto","/contactos/perfil/"+a.id+"/edit","modal-lg")}};var $table=$("#table");$table.bootstrapTable({loadingFontSize:"16px"}),$("#modal").on("hidden.bs.modal",function(o){$table.bootstrapTable("refresh")});