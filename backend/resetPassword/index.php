<?php 
date_default_timezone_set('America/Bogota');
if(!isset($_GET['repair'])){
    echo json_encode(array('Error'=> 'No tienes permisos.'));
    exit();
}else{
    $data = json_decode(base64_decode($_GET['repair']), true);
    $postJson =  $_GET['repair'];

    if(empty($_GET['repair'])){
      echo json_encode(array('Error'=> 'Datos incorrectos'));
      exit();
    }else{
      if(!is_array($data)){
        echo json_encode(array('Error'=> 'No tienes permisos.'));
        exit();
      }else{
          $time1 = date_create($data['dateSolicitud']);
          $time2 = date_create(date("Y-m-d H:i"));
          $timeExpire = date_diff($time1,$time2);
          if($timeExpire->invert == 0){ // que la fecha actual sea mayor a la guardada

              if($timeExpire->h >= 1){ // Si ha pasado mas de una inhabilitamos el acceso a la restauracion
                  echo json_encode(array('Error'=> 'Esta solicitud ha expirado.'));
                  exit();
              }

          }
      }
    }


}

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <link rel='shortcut icon' type='image/x-icon' href='public/images/e.ico' />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=1,initial-scale=1,user-scalable=1" />
  <title>Recuperacion Contraseña</title>
  <link rel="stylesheet" type="text/css" href="css/style.css" />
  <link rel="stylesheet" type="text/css" href="css/loading.css" />
</head>
<body>
  <div class="container">
    <div class="header">
      <img src="../resources/images/logo.png" alt="">
    </div>
    <div class="clearfix"></div>
    <div class="body">
      <form method="post" id="formResetPwd" action="resetController" role="login" id="login_form" autocomplete="off" >
        <h2 class="text-center">Actualizar Contraseña</h2>
        <input type="password" autocomplete="off" name="pwd" id="pwd" placeholder="Contraseña" required class="form-control input-lg" />
        <input type="password" autocomplete="off" name="pwd2" id="pwd2" placeholder="Repetir Contraseña" required class="form-control input-lg" />
        <input type="hidden" name="dataPost" value="<?php echo $postJson ?>" >
        <button type="submit" name="saveRestet" class="btn btn-lg btn-primary btn-block">Actualizar</button>
      </form>
    </div>
    <div class="clearfix"></div>
    <div class="footer">
      Todos los derechos Reservados - Desarrollado por: MDC Colombia SAS
    </div>
  </div>
  <script src="js/jquery.js"></script>
  <script type="text/javascript" src="js/forgetPwd.js"></script>

  <script>
    
  </script>
</body>
</html>