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

// function configuracion(){
//   // $.post("../ajax/configuracion.php?op=listarActiva", function(data, status)
//   // {
//   //   data = JSON.parse(data);
//   //   $(".empresa").val(data.nombre);
//   //   // $(".login-page").val(data.alias);
//   // });
//   $.post("../ajax/configuracion.php?op=listarActiva", function(data){
//     //data = JSON.parse(data);
//     //$(".empresa").val(data.nombre);
//     // $("#idcategoria").html(r);
//     // $('#idcategoria').selectpicker('refresh');
//   });
// }

function showHidePwd(){
  $('#clavea').attr('type', $('#clavea').is(':password') ? 'text' : 'password');
  if ($('#clavea').attr('type') === 'password') {
    $('#eye').removeClass('fa-eye').addClass('fa-eye-slash');
  } else {
    $('#eye').removeClass('fa-eye-slash').addClass('fa-eye');
  }
}

init();