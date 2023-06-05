<?php 
class resetPass {

  function __construct($data){

    date_default_timezone_set('America/Bogota');
    require_once(__DIR__.'/../conexion/conexion.php');
    require_once( __DIR__ . '/../models/classBD.php');
    $this->model = (new classBD);
    $this->params = $data;
    if(isset($this->params["function_name"])){
      if(method_exists($this,$this->params["function_name"])){
        return $this->{$this->params["function_name"]}();
      }else{
        $this->response([],500,"Error en el envío de datos");
      }
    }else{
      $this->response([],500,"Error en el envío de datos");
    }
  }
  protected function response($data,$code,$msj=""){
    header('Content-Type: application/json');
    $resp = array(
        "code" => $code,
        "message" => $msj,
        "result" => $data,
    );            
    die(json_encode($resp,JSON_PRETTY_PRINT));
  }
  protected function encrypt_decrypt($action, $string){
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = $this->env("KEY_ENCRYPT");
    $secret_iv = $this->env("IV_SECRET");
    // hash
    $key = hash('sha256', $secret_key);
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if( $action == 'decrypt' ) {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
  }
  protected function resetPassword(){
    $dataForget =  json_decode(base64_decode($this->params["dataPost"]),true);
    $save = $this->model->saveTable("users",[
      "password" => $this->encrypt_decrypt("encrypt", $this->params["pwd"])
    ]," WHERE id = {$dataForget['usrId']}");
    if($save){
      $msj = "Se actualizo tu contraseña Correctamente";
      $code=200;
    }else{
      $code=500;
      $msj = "Error al actualizar la contraseña, intenta mas tarde, si el error persiste comuniquese con el administrador del sitio";
    }
    $this->response([],$code,$msj);

  }
  protected function env($key){
    return $_ENV[$key];
}
}(new resetPass($_REQUEST))

?>