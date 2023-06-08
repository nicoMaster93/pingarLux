<?php 
        /**
         * Automatically generated script 
         * Creation Date: 2023-06-01 16:16:00 
         * Autor: Nicolas Hernandez 
         * version:1
         * Cliente:Pingar Lux
         * Informacion: 
         * Esta clase contiene los metodos que se van a utilizar en los servicios que se generan, con el fin de validar parametros, logs y respuestas http.
         **/
        
         class Controller{
            protected $param;
            protected $model;
            protected $table;
            protected $requestApp;
            protected $jsonBody;
            function __construct($postData){
                try{
                    include_once(__DIR__."/../models/classBD.php");
                    $this->model = (new classBD);
                    $this->params = $postData;
                    $this->requestApp = $this->params["requestApp"];
                    $jsonBody = json_decode(file_get_contents("php://input"),true);
                    if(is_array($jsonBody)){
                        if(count($jsonBody) > 0 ){
                            $this->params = array_merge($this->params, $jsonBody);
                        }
                    }
                    $this->logs();   
                }catch(Exception $e) {
                    return $this->response([],500,$e->getMessage());
                }
            }
            protected function validParameters($accion="",$parametrosValidos = array(),$description=""){
                $req = array();
                if(!empty($description)){
                    $description = "\n\n<h4>Descripción de la acción</h4>\n\n<p>$description</p>";
                }
                $ernoTitle = $description."\n\n<h4>Parametros de la acción <b>[ $accion ]</b></h4><p>Estos son los parametros requeridos</p>\n";
                $error = false;
                
                foreach ($parametrosValidos as $key => $value) {
                    if(is_array($value) && array_key_exists("optional_vars",$parametrosValidos)){
                        $errorParam = "\n\n<h4> Parametros Opcionales  <b>[ $accion ]</b> </h4>\n";
                        $errorParam .= "\n<li> @Param [debugAction] => boolean (true/false) || Testea el servicio en ambiente de pruebas, aplica unicamente para los servicios que: [Inserten, Actualicen o Eliminen]. </li>";
                        array_push($req,$errorParam);
                        foreach ($value as $ko => $vo) {
                            $errorParam = "\n<li> @Param [$ko] =>  $vo . </li>";
                            array_push($req,$errorParam);
                        }
                    }else{
                        if(!array_key_exists($key,$this->params)){
                            $error = true;
                        }
                        if(empty($value)){
                            $value = "Campo Obligatorio";
                        }
                        $errorParam = "\n<li> @Param [$key] => $value . </li>";
                        array_push($req,$errorParam);
                        
                    }
                }
                if($error){
                    $resp = array("error" => $error, "msj" => $ernoTitle.implode("",$req));
                }else{
                    $resp = array("error" => $error);
                }
                return $resp;
            }
            protected function addOptionalVarsData($post,$key){
                try{
                    /**
                     * agrego los valores que vienen como opcional y devuelvo el arreglo con la posicion nueva
                     */
                    $keyParam = $keyPost = $key;
                    if(is_array($key)){
                        $keyParam = $key[0];
                        $keyPost = $key[1];
                    }
                    if(array_key_exists($keyParam,$this->params) && !empty($this->params[$keyParam]) ){
                        $post[$keyPost] = $this->params[$keyParam];
                    }
                    return $post;
                }catch(Exception $e) {
                    return $this->response([],500,$e->getMessage());
                }
            }
            protected function sendDataCurl($body,$headers=array()){
                try{
                    /**
                     * Envia datos por curl el @param body es un array [url => "", data => ""]
                     */
                    if(count($headers) == 0){
                        $headers = array(
                            "Content-Type: application/json"
                        );
                    }
                    $ch = curl_init();
                    curl_setopt( $ch,CURLOPT_URL, $body["url"]);
                    curl_setopt( $ch,CURLOPT_POST, true );
                    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $body["data"] ) );
                    $result = curl_exec($ch );
                    curl_close( $ch );
                    return json_decode($result,true);
                }catch(Exception $e) {
                    return $this->response([],500,$e->getMessage());
                }
            }
            protected function logs($msj=""){
                if(empty($msj)){
                    $msj = "[".date("Y-m-d H:i:s")."] Parametros recibidos\n" . print_r($this->params,1);
                }else{
                    $msj = "\n[".date("Y-m-d H:i:s")."] ". print_r($msj,1);
                }
                $fileName = __DIR__ . "/../logs/logService-".date("Y-m-d").".log";
                error_log($msj, 3, $fileName );
                chmod($fileName, 0664);
            }
            protected function response($data,$code,$msj=""){
                $resp = array(
                    "code" => $code,
                    "message" => $msj,
                    "result" => $data,
                );            
                // $this->logs(print_r($resp,1));     
                return $resp;
            }
            protected function deleteFiles($mistakesUpload,$successUpload,$force=false){
                try{
                    /* Recorremos Archivos que se guardaron y los borramos si existe error en alguno */
                    if(count($mistakesUpload) > 0 || $force){
                        $msjErr = "";
                        for ($a=0; $a < count($mistakesUpload); $a++) { 
                            $msjErr .="[".$mistakesUpload[$a]["file"]."] : " . $mistakesUpload[$a]["error"] . "\n";
                        }
                        for ($b=0; $b <count($successUpload) ; $b++) { 
                            if(file_exists($successUpload[$b])){
                                unlink($successUpload[$b]);
                            }
                        }
                        if(count($mistakesUpload) > 0){
                            throw new Exception($msjErr, 1);
                        }
                    }
                    return true;
                }catch(Exception $e) {
                    return $this->response([],500,$e->getMessage());
                }
            }

            protected function uploadFiles($params, $keyFile, $folder, $allowedTypes, $dataOld=[] ){
                try{
                    /* validamos si vienen imagenes */
                    $this->params = $params;
                    $this->allowedTypes = $allowedTypes;
                    if($this->params[$keyFile]){
                        $folderAssets = __DIR__ . "/../assets/";
                        /* Creamos folder assets si no existe */
                        if(!is_dir($folderAssets)){
                            mkdir($folderAssets,0777);
                        }
                        /* Creamos folder donde se va subir el contenido si este no existe */
                        $folderImage = $folderAssets . "{$folder}/";
                        if(!is_dir($folderImage)){
                            mkdir($folderImage,0777);
                        }
                        /* valido si viene como FILE */
                        if(!isset($this->params[$keyFile]["tmp_name"])){
                            throw new Exception("El archivo no se envió en un formato valido Solo se admite tipo FILE", 1);
                        }
                        $mistakesUpload = [];
                        $successUpload = [];
                        $post = [];
                        $filePost = (is_string( $this->params[$keyFile]["tmp_name"] ) ? [ $this->params[$keyFile]["tmp_name"] ] : $this->params[$keyFile]["tmp_name"] );
                        
                        if(isset($this->params[$keyFile]) && !empty($this->params[$keyFile]) ){
                            $filePath = $this->params[$keyFile]["tmp_name"];
                            $arrFile = explode("." , $this->params[$keyFile]["name"]);
                            $ext = end($arrFile);
                            $fileName = uniqid() . "." . $ext;
                            $tmpFileName = $folderImage . $fileName;
                            $this->logs(["filePath FILE ", filesize($filePath)]);
                            if(filesize($filePath) > 0 ){
                                /* validamos la extension */
                                if(in_array($ext,$this->allowedTypes)){
                                    if(move_uploaded_file($filePath, $tmpFileName)){
                                        $successUpload[] = $tmpFileName;
                                        $post[$keyFile][] = str_replace(__DIR__,"",$tmpFileName);
                                        $this->logs("UPLOAD FILE {$tmpFileName}");
                                    }else{
                                        $this->logs("ERROR FILE {$tmpFileName}");
                                        $mistakesUpload[] = ["file" => $this->params[$keyFile]["name"], "error" => "El archivo no se pudo subir al servidor "];
                                    }
                                }else{
                                    $this->logs("ERROR FILE {$tmpFileName}");
                                    $mistakesUpload[] = ["file" => $this->params[$keyFile]["name"], "error" => "El archivo no tiene una extension válida, solo se admiten formatos [" . implode(",", $this->allowedTypes) . "]"];
                                }
                            }else{
                                // if(isset($dataOld))
                                if($dataOld && empty($dataOld[$keyFile])){
                                    $this->logs("NOT FILE UPLOAD FORM {$tmpFileName}");
                                    $mistakesUpload[] = ["file" => $this->params[$keyFile]["name"], "error" => "El archivo está dañado"];
                                }else{
                                    $this->logs("FILE EXIST " . $dataOld[$keyFile] );
                                }
                            }
                        }
                        
                        $result = [
                            "mistakesUpload" => $mistakesUpload,
                            "successUpload" => $successUpload,
                            "post" => $post
                        ];
                        return $this->response($result,200,"");
                    }
                    throw new Exception("No existe archivos asociados a esa key", 1);
                }catch(Exception $e) {
                    return $this->response([],500,$e->getMessage());
                }
            }

            protected function is_json($string) {
                json_decode($string);
                return (json_last_error() == JSON_ERROR_NONE);
            }
            protected function encrypt_decrypt($action, $string){
                $output = false;
                $encrypt_method = "AES-256-CBC";
                $secret_key = $this->env("KEY_ENCRYPT");
                $secret_iv = $this->env("IV_SECRET");
                // hash
                $key = hash("sha256", $secret_key);
                // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
                $iv = substr(hash("sha256", $secret_iv), 0, 16);
                if ( $action == "encrypt" ) {
                    $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
                    $output = base64_encode($output);
                } else if( $action == "decrypt" ) {
                    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
                }
                return $output;
            }
            protected function is_valid_email($email){
                return (false !== filter_var($email, FILTER_VALIDATE_EMAIL));
            }

            protected function formatBodyEmail($body){
                $i = rand(6,11);
                $texto = "Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus odit ducimus nesciunt mollitia repellat, fuga vitae molestias quis id! Ut quo alias sed repellat sit itaque quidem corporis, vitae labore.";
                $urlBase = $this->env("CLIENTE_URL") . "/backend/";
                $template = str_replace(
                    ["[TEXTO]","[RAND]","[VERSION]","[BASE_URL]"],
                    [$body, $i,time(),$urlBase],
                    file_get_contents(__DIR__ . "/../email.php")
                );
                
                return $template;
            }
            protected function sendEmail($to, $from, $subject, $body, $files=[]){
                
                $message = $this->formatBodyEmail($body);

                // Configura los encabezados del correo
                $headers = "From: $from\r\n";
                $headers .= "Reply-To: {$this->env("EMAIL_WEBSITE")}\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: multipart/mixed; boundary=\"boundary\"\r\n";

                // Crea el mensaje con el cuerpo en formato HTML
                $multipart_message = "--boundary\r\n";
                $multipart_message .= "Content-Type: text/html; charset=UTF-8\r\n";
                $multipart_message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
                $multipart_message .= "$message\r\n\r\n";

                // Agrega los archivos adjuntos
                if(!empty($files)){
                    $files = array(
                        "ruta/archivo1.jpg",
                        "ruta/archivo2.zip",
                        "ruta/archivo3.pdf",
                        "ruta/archivo4.docx"
                    );
                    foreach ($files as $file) {
                        $filename = basename($file);
                        $file_content = file_get_contents($file);
                        $file_encoded = base64_encode($file_content);
    
                        $multipart_message .= "--boundary\r\n";
                        $multipart_message .= "Content-Type: application/octet-stream; name=\"$filename\"\r\n";
                        $multipart_message .= "Content-Description: $filename\r\n";
                        $multipart_message .= "Content-Disposition: attachment; filename=\"$filename\"; size=" . filesize($file) . ";\r\n";
                        $multipart_message .= "Content-Transfer-Encoding: base64\r\n\r\n";
                        $multipart_message .= chunk_split($file_encoded) . "\r\n\r\n";
                    }
                    /* Cierro el bounday de los adjuntos */
                }
                $multipart_message .= "--boundary--\r\n";
                // Envía el correo electrónico
                $this->logs([$to, $subject, $multipart_message, $headers]);
                if($this->env("MODE_ENV") == "develop"){
                    return true;
                }
                if (mail($to, $subject, $multipart_message, $headers)) {
                    return true; // "El correo ha sido enviado correctamente.";
                } else {
                    return false; // "Error al enviar el correo.";
                }
            }
            protected function env($key){
                return $_ENV[$key];
            }
            protected function generateUUID() {
                $uuid = md5(uniqid('', true) . time());
                return sprintf(
                    '%s-%s-%s-%s-%s',
                    substr($uuid, 0, 8),
                    substr($uuid, 8, 4),
                    substr($uuid, 12, 4),
                    substr($uuid, 16, 4),
                    substr($uuid, 20, 12)
                );
            }
            /**
             * Fin de los métodos internos de la clase 
             */
            
        }
         ?>