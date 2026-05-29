function confirmarDesactivacion(url,texto){

Swal.fire({

title:'¿Continuar?',
text:texto,
icon:'warning',
showCancelButton:true,
confirmButtonText:'Sí, continuar',
cancelButtonText:'Cancelar',
confirmButtonColor:'#0d6efd'

}).then((result)=>{

if(result.isConfirmed){
window.location.href = url;
}

});

}