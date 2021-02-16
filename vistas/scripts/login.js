//Función que se ejecuta al inicio
function init(){

  //configuracion();

  $("#frmAcceso").on('submit',function(e)
  {
    validarUsuario(e);  
  });
}


function validarUsuario(e){
  e.preventDefault();
  logina=$("#logina").val();
  clavea=$("#clavea").val();

  $.post("../ajax/usuario.php?op=verificar",
    {"logina":logina,"clavea":clavea},
    function(data)
    {
      if (data!="null")
      {
        $(location).attr("href","escritorio.php");            
      }
      else
      {
        //OK
        Swal.fire({
          position: 'top-end',
          icon: 'error',
          title: 'Usuario y/o Password incorrectos!',
          showConfirmButton: false,
          timer: 3000,
          showClass: {
            popup: 'animate__animated animate__fadeInDown'
          },
          hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
          },
          showCloseButton: true,
          focusConfirm: false,
          timerProgressBar: true,
        });
      }
  });
}

function showHidePwd(){
  $('#clavea').attr('type', $('#clavea').is(':password') ? 'text' : 'password');
  if ($('#clavea').attr('type') === 'password') {
    $('#eye').removeClass('fa-eye').addClass('fa-eye-slash');
  } else {
    $('#eye').removeClass('fa-eye-slash').addClass('fa-eye');
  }
}

init();