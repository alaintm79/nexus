"use strict";var vigencia=document.getElementById("vigencia");function suplementoVigencia(){var e=vigencia.selectedOptions[0].label,n=document.getElementById("vigenciaFecha"),i=document.getElementById("fechaVigencia");n.classList.toggle("d-none","CUMPLIMIENTO FECHA"!==e),"CUMPLIMIENTO FECHA"===e?(i.disabled=!1,i.required=!0):(i.disabled=!0,i.required=!1)}window.addEventListener("load",function(){Inputmask({removeMaskOnSubmit:!0}).mask(document.querySelectorAll("input")),suplementoVigencia()}),vigencia.addEventListener("change",function(e){suplementoVigencia()},!1);