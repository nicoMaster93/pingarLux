/**
 * @description Valida los carácteres de la contraseña
*/
let validNewpass = false;

function ajaxJquery(page,frmData,metod,funcionResp,datalast){
  
      $.ajax({
        type:metod,
        url: page+'.php',
        data:frmData,
        cache: false,
        contentType: false,
        processData: false,
        success:function(data){
          // console.log(data);
          if(typeof funcionResp != 'undefined'){
            funcionResp(data,datalast);
          }
         },
        error: function(data){
          console.log(data);
          if(data.status == 404){
            console.log("Esta pagina no ha sido encontrada, error 404 ","Error 404");
          }
        }
      });

}
function validarEmail(valor){
    if (/^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i.test(valor)){
     //alert("La dirección de email " + valor + " es correcta.");
     return true;
    } else {
     //alert("La dirección de email es incorrecta.");
     return false;
    }
  }

$('#modalForget').on('hidden.bs.modal', function (e) {
    $("#repairPwd").removeClass("input_error");
    $("#repairPwd").val("");
    $("#alertFo").remove();
})
$(function(){

  $("#formResetPwd").on('submit', function(e){
    
  	e.preventDefault();
  	loading();
  	if($("#alertFo")){
      $("#alertFo").remove();
    }
    
    if(validNewpass){
      if($("#pwd").val().trim() == '' || $("#pwd2").val().trim() == '' ){
        var txtError = $("<span id='alertFo' class='text-danger'></span>").text("* Ambos Campos son obligatorios");
        $("#pwd2").after(txtError);
        loading();
      }else{
        if($("#pwd").val().trim() == $("#pwd2").val().trim()){
          /* Elimino la caja de las validaciones de los caracteres del pass */
          $(".validate").remove();

          var frm = document.getElementById('formResetPwd');
          var formData = new FormData(frm);
          formData.append('function_name','resetPassword');
          ajaxJquery(this.action,formData,'POST',(response)=>{ 
            
            if(response['code'] == 200){
                var txtError = $("<span id='alertFo' class='text-success'></span>").html("* "+response['message']);
                setTimeout(function(){
                    $("#formResetPwd")[0].reset();
                },2800);
            }else{
                var txtError = $("<span id='alertFo' class='text-danger'></span>").html("* "+response['message']);
            }

            $("#pwd2").after(txtError);
            loading();
          });
        }else{
          loading();
          var txtError = $("<span id='alertFo' class='text-danger'></span>").text("* Ambas Contraseñas deben ser iguales");
            $("#pwd2").after(txtError);
        }
      } 
    }else{
      loading();
      var txtError = $("<span id='alertFo' class='text-danger'></span>").text("* La Contraseña no cumple con los mínimos requisitos");
      $("#pwd2").after(txtError);
    }
  });
  
  $('#pwd').keyup(function() {
    validateFirstPassword($("#pwd"));
  });

})

function loading(){
	
	if($("#containerLoading").length == 0 ){
		var htmlLoading = $('<div class="containerLoading in" id="containerLoading"></div>');
		htmlLoading.html(`
    <div class="doubleLine"> 
      <div></div> 
      <div></div> 
      <div> 
        <div></div> 
        </div><div> 
        <div></div> 
      </div> 
    </div>`);
		$("body").append(htmlLoading);
	}else{
		$("#containerLoading").removeClass('in');
		setTimeout(function(){ // para usos practicos y evitar que se quede el preload infinitamente si no se llama para inactivar
			$("#containerLoading").remove();
		},500);
	}
}

function validateFirstPassword(passDOM) {
  $("#alertFo").remove();
  
  var contenido = '<ul class="validate">';
  var np1 = passDOM.val();
  var len = np1.length;
  var nums = /[0-9]/;
  var spec = /[$,%,&,@,#,¿,?,\-,.,*,+,\/,(,),!,\[,\[,=,\,,",',:]/;
  var mayu = /[A-Z]/;
  var error = 0;
  
  /* Seteo la validacion */
  validNewpass = false;

  if (len < 8) {
    contenido = contenido + '<li><span class="glyphicon-remove" >x</span> Minimo 8 caracteres</li>';
    error++;
  } else {
    contenido = contenido + '<li><span class="glyphicon-ok" >✔</span> Minimo 8 caracteres</li>';
  }

  if (!nums.test(np1)) {
    contenido = contenido + '<li><span class="glyphicon-remove" >x</span> Minimo 1 número</li>';
    error++;
  } else {
    contenido = contenido + '<li><span class="glyphicon-ok" >✔</span> Minimo 1 número</li>';
  }

  if (!mayu.test(np1)) {
    contenido = contenido + '<li><span class="glyphicon-remove" >x</span> Minimo 1 mayúscula</li>';
    error++;
  } else {
    contenido = contenido + '<li><span class="glyphicon-ok" >✔</span> Minimo 1 mayúscula</li>';
  }

  if (!spec.test(np1)) {
    contenido = contenido + '<li><span class="glyphicon-remove" >x</span> Minimo 1 caracter especial ($ % & @ # ? !)</li>';
    error++;
  } else {
    contenido = contenido + '<li><span class="glyphicon-ok" >✔</span> Minimo 1 caracter especial ($ % & @ # ? !)</li>';
  }
  
  if(error == 0){
    validNewpass = true;
  }
    
  contenido = contenido + '</ul>';
  console.log(contenido, error, validNewpass);

  if($(".validate").length > 0 ){
    $(".validate").remove();
    passDOM.after($(contenido))
  }else{
    passDOM.after($(contenido))
  }

}
