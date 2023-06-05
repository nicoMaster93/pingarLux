<?php
class LoadService {
    protected $model;
    protected $headers;
    protected $mode;
    function __construct(){
        date_default_timezone_set('America/Bogota');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: *');
        header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
        header('content-type: application/json; charset=utf-8');
        require_once('conexion/conexion.php');
        require_once( __DIR__ . '/models/classBD.php');
        $this->model = (new classBD);
        $this->headers = apache_request_headers();
        if($this->autentication()){
            $this->ini();
        }
    }
    protected function writeLog($msj){
        $msj = $msj = "\n[".date("Y-m-d H:i:s")."] Headers recibidos\n " . print_r($msj,1);
        $fileName = __DIR__ . '/logs/logService-'.date("Y-m-d").'.log';
        error_log(print_r($msj,1), 3, $fileName );
        chmod($fileName, 0664);
    }
    protected function autentication(){
        /* Valido los headers */
        try {
            $this->writeLog($this->headers);
            if($this->getEnv("DB_CONNECT") == "true"){
                if(!isset($this->headers['Authorization']) && !isset($this->headers['authorization']) ){
                    throw new Exception("Autenticación inválida.\n Valide el token de autenticacion, si el problema persiste consulte con el administrador");   
                }else{
                    $au = isset($this->headers['Authorization']) ? $this->headers['Authorization'] : $this->headers['authorization'];
                    
                    if(strpos($au, "Bearer") === false){
                        throw new Exception("Autenticación inválida.\n Valide el token de autenticacion, si el problema persiste consulte con el administrador");       
                    }
                    $token = trim(explode("Bearer", $au)[1]);
                    if(!isset($this->headers['mode_api'])){
                        throw new Exception("Argumento [mode_api] en el headers es Inválido. Consulte con el administrador");
                    }else{
                        $this->mode = $this->headers['mode_api'];
                        $mode_api_allowed = $this->model->getDataTableBySql("SELECT LOWER(group_concat(app)) grp_app FROM allowed_tokens WHERE status = ? ", [1]);
                        if(empty($this->mode) || !in_array(strtolower($this->mode), explode("," , $mode_api_allowed[0]['grp_app'])) ){
                            throw new Exception("Argumento [mode_api] en el headers es Inválido. Consulte con el administrador del api");
                        }
                    }
                }
                $au = $this->model->getDataTableBySql("SELECT * FROM allowed_tokens WHERE token = ? and app regexp ? ", [$token,$this->mode], true);
                if(empty($au)){
                    throw new Exception("El [token] o el [mode_api] es inválido. Consulte con el administrador", 1);
                }else{
                    return true;
                }
            }else{
                $this->mode = "backend";
                return true;
            }
        } catch (\Throwable $e) {
            die($this->response([],500,$e->getMessage()));
        }
    }
    protected function ini(){
        try {
            $endPoint = $_GET['endpoint'];
            $action = $_GET['action'];
            $folder = __DIR__ . DIRECTORY_SEPARATOR . $this->getEnv("FOLDER_SERVICE") . DIRECTORY_SEPARATOR . $endPoint;
            $controller = __DIR__ . DIRECTORY_SEPARATOR . $this->getEnv("FOLDER_SERVICE") . DIRECTORY_SEPARATOR . "Controller.php";
            $routeInterface = $folder . DIRECTORY_SEPARATOR . "{$endPoint}ControllerInterfaces.php";
            $route = $folder . DIRECTORY_SEPARATOR . "{$endPoint}Controller.php";

            /* validamos la ruta del servicio */
            if(!is_dir($folder) || !file_exists($route)){
                throw new Exception("El servicio solicitado no existe [$route]", 1);
            }else{
                require_once($controller);
                /* Por ahora condiciono la interfaz, ya que puede que no todas las clases tenga interfaz implementada */
                if(file_exists($routeInterface)){
                    require_once($routeInterface);
                }
                require_once($route);
                $requestMethod = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'post';
                $postData = $_REQUEST;
                $postData['requestMethod'] = strtoupper($requestMethod);
                $classname = "{$endPoint}Controller";
                // valido si se envia un $_FILES
                if(count($_FILES) > 0){
                    foreach ($_FILES as $key => $value) {
                        $postData[$key] = $value;
                    }
                }
                /* Agrego el tipo de aplicacion que esta consumiendo el api y el http-request */
                $postData['requestApp'] = strtolower($this->mode);
                $postData['requestMethod'] = strtoupper($requestMethod);

                if(class_exists($classname)){
                    $api = new $classname($postData);
                    if(method_exists($api,$action)){
                        $resp = $api->$action();
                        return $this->response( $resp['result'], $resp['code'], $resp['message']);
                    }else{
                        throw new Exception("La acción solicitada no existe [$action]", 1);
                    }
                }
            }

            echo $route;
        } catch (\Throwable $e) {
            die($this->response([],500,$e->getMessage()));
        }

    }
    protected function response($data,$code,$msj=''){
        // http_response_code($code);
        $resp = json_encode([
            'code' => $code,
            'message' => $msj,
            'result' => $data,
        ], JSON_PRETTY_PRINT);
        echo $resp;
    }
    protected function getEnv($key){
        return $_ENV[$key];
    }
}(new LoadService());


?>