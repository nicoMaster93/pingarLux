<?php 
        /**
         * Automatically generated script 
         * Creation Date: 2023-06-01 16:16:00 
         * Autor: Nicolas Hernandez 
         * version:1
         * Cliente:Pingar Lux
         * Informacion: 
         * Esta clase contiene los servicios que se van a utilizar, inicialmente la clase contiene un servicio (mtodo) de ejemplo; 
         * el cual ayuda a entender el desarrollo de un servicio autodocumentado facilitando luego el uso del mismo.
         **/
        
        class BookingController extends Controller implements BookingControllerInterfaces{
            
            function __construct($postData){
                try{
                    parent::__construct($postData);
                    $this->table = "Booking";
                }catch(Exception $e) {
                    return $this->response([],500,$e->getMessage());
                }
            }
            
            public function getAllBooking(){ 
                try{
                    /* Se agregan al arreglo los parametros requeridos para el funcionamiento del metodo */
                    /* optional_vars => Campos por lo cual se desea filtrar */
                    $paramRequired = array(
                        "lng" => "Idioma (en|es)",
                        "optional_vars" => array(
                        )
                    );
                    $validMethods = ["GET"];
                    $erno = self::validParameters("getAllBooking",$paramRequired,"Obtiene el listado de Booking");
                    // validacion de parametros
                    $success = false;
                    if($erno["error"]){
                        throw new Exception($erno["msj"], 1);
                    }elseif(!in_array($this->params["requestMethod"],$validMethods)){
                        throw new Exception("El Método HTTP es incorrecto \nMétodos http request admitidos [" . implode(",", $validMethods) ."]", 1);
                    }else{
                        $fileIncludes = __DIR__ . "/../../setupSupplies/" .$this->params["lng"] . "/includes.json";
                        $this->dataIncludes = json_decode(file_get_contents($fileIncludes),true);

                        $file = __DIR__ . "/../../setupSupplies/" .$this->params["lng"] . "/accommodations.json";
                        $data = json_decode(file_get_contents($file),true ) ;
                        
                        $newArray = array_map(function($element) {
                            $includes_obj = [];
                            for ($i=0; $i <count($element["includes"]); $i++) { 
                                $includes_obj[] = $this->dataIncludes[ $element["includes"][$i] ];
                            }
                            $element["includes"] = $includes_obj;
                            return $element; // Regresa el elemento modificado
                        }, $data);
                        $data = $newArray;


                        if(count($data)>0){
                            return $this->response($data,200);
                        }else{
                            return $this->response($data,400,"No hay registros");
                        }
                    }

                }catch(Exception $e) {
                    return $this->response([],500,$e->getMessage());
                }
            }
            public function getAllProfiles(){ 
                try{
                    /* Se agregan al arreglo los parametros requeridos para el funcionamiento del metodo */
                    /* optional_vars => Campos por lo cual se desea filtrar */
                    $paramRequired = array();
                    $validMethods = ["GET"];
                    $erno = self::validParameters("getAllProfiles",$paramRequired,"Obtiene el listado de Perfiles");
                    // validacion de parametros
                    $success = false;
                    if($erno["error"]){
                        throw new Exception($erno["msj"], 1);
                    }elseif(!in_array($this->params["requestMethod"],$validMethods)){
                        throw new Exception("El Método HTTP es incorrecto \nMétodos http request admitidos [" . implode(",", $validMethods) ."]", 1);
                    }else{

                        $file  = __DIR__ . "/../../setupSupplies/team/team.json";
                        $data = json_decode(file_get_contents($file),true ) ;
                        
                        if(count($data)>0){
                            return $this->response($data,200);
                        }else{
                            return $this->response($data,400,"No hay registros");
                        }
                    }

                }catch(Exception $e) {
                    return $this->response([],500,$e->getMessage());
                }
            }
            
            /**
            * Fin de los métodos públicos
            */
        
        
        }
         ?>