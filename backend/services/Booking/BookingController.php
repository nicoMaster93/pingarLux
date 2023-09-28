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
            public function getAllImages(){ 
                try{
                    /* Se agregan al arreglo los parametros requeridos para el funcionamiento del metodo */
                    /* optional_vars => Campos por lo cual se desea filtrar */
                    $paramRequired = array();
                    $validMethods = ["GET"];
                    $erno = self::validParameters("getAllImages",$paramRequired,"Obtiene el listado de imagenes para el Banner");
                    // validacion de parametros
                    $success = false;
                    if($erno["error"]){
                        throw new Exception($erno["msj"], 1);
                    }elseif(!in_array($this->params["requestMethod"],$validMethods)){
                        throw new Exception("El Método HTTP es incorrecto \nMétodos http request admitidos [" . implode(",", $validMethods) ."]", 1);
                    }else{

                        $file  = __DIR__ . "/../../setupSupplies/en/accommodations.json";
                        $data = json_decode(file_get_contents($file),true ) ;
                        $images = [];
                        foreach ($data as $key => $value) {
                            $images = array_merge($images, $value["pictures"]);
                        }

                        if(count($images)>0){
                            return $this->response($images,200);
                        }else{
                            return $this->response($images,400,"No hay registros");
                        }
                    }

                }catch(Exception $e) {
                    return $this->response([],500,$e->getMessage());
                }
            }
            public function checkAvailability(){ 
                try{
                    /* Se agregan al arreglo los parametros requeridos para el funcionamiento del metodo */
                    /* optional_vars => Campos por lo cual se desea filtrar */
                    $paramRequired = array(
                        "lng" => "Lenguaje",
                        "fechaIni" => "Fecha Ingreso",
                        "fechaEnd" => "Fecha Salida",
                        "adults" => "Adultos",
                        "children" => "Niños",
                        "email" => "Correo del interesado"
                    );

                    $validMethods = ["POST"];
                    $erno = self::validParameters("checkAvailability",$paramRequired,"Valida disponibilidad de reservacion");
                    // validacion de parametros
                    $success = false;
                    if($erno["error"]){
                        throw new Exception($erno["msj"], 1);
                    }elseif(!in_array($this->params["requestMethod"],$validMethods)){
                        throw new Exception("El Método HTTP es incorrecto \nMétodos http request admitidos [" . implode(",", $validMethods) ."]", 1);
                    }else{

                        if(strtotime($this->params["fechaEnd"]) < strtotime($this->params["fechaIni"])){
                            throw new Exception("La fecha de Salida debe ser mayor que la reservación", 1);
                        }else if(strtotime($this->params["fechaIni"]) < strtotime("now")){
                            throw new Exception("La fecha de reservación debe ser mayor al día actual", 1);
                        }
                        
                        $file  = __DIR__ . "/../../setupSupplies/booking/booking.json";
                        $data = json_decode(file_get_contents($file),true ) ;
                        
                        $data[] = [
                            "id" => $this->uuid(),
                            "fechaIni" => $this->params["fechaIni"],
                            "fechaEnd" => $this->params["fechaEnd"],
                            "adults" => $this->params["adults"],
                            "children" => $this->params["children"],
                            "email" => $this->params["email"],
                            "lng" => $this->params["lng"] ?? "en"
                        ];

                        if(file_put_contents($file, json_encode( $data, JSON_PRETTY_PRINT) )){
                            
                            $to = $this->env("EMAIL_WEBSITE_ADMIN");
                            $from = $this->env("EMAIL_WEBSITE");
                            $subject = "Informacíon Disponibilidad de Reservación";
                            $body = "
                                    <p><b>Fecha:  ".date("Y-m-d H:i")."</b></p>
                                    <p>Se ha registrado una nueva solicitud de reservación</p>\n
                                    <dl>
                                        <dt><b>Email</b></dt>
                                        <dt>{$this->params["email"]}</dt>
                                        <dt><b>Adultos: </b> {$this->params["adults"]} | <b>Niños: </b>{$this->params["children"]}</dt>
                                        <dt><b>Check In: </b> {$this->params["fechaIni"]} | <b>Check out: </b>{$this->params["fechaEnd"]}</dt>
                                    </dl>
                                    ";
                            $email = $this->sendEmail($to, $from, $subject, $body);
                            if($email){
                                return $this->response([$email],200, "Se envió el correo correctamente");
                            }else{
                                return $this->response([$email],400,"Error en el envío del formulario");
                            }
                        }

                    }

                }catch(Exception $e) {
                    return $this->response([],500,$e->getMessage());
                }
            }
            public function sendContactInformation(){ 
                try{
                    /* Se agregan al arreglo los parametros requeridos para el funcionamiento del metodo */
                    /* optional_vars => Campos por lo cual se desea filtrar */
                    $paramRequired = array(
                        "lng" => "Lenguaje Seleccionado",
                        "name_contact" => "Nombre",
                        "email_contact" => "Correo del contacto",
                        "comment_contact" => "Comentario",
                    );

                    $validMethods = ["POST"];
                    $erno = self::validParameters("sendContactInformation",$paramRequired,"Envia formulario de contáctanos");
                    // validacion de parametros
                    $success = false;
                    if($erno["error"]){
                        throw new Exception($erno["msj"], 1);
                    }elseif(!in_array($this->params["requestMethod"],$validMethods)){
                        throw new Exception("El Método HTTP es incorrecto \nMétodos http request admitidos [" . implode(",", $validMethods) ."]", 1);
                    }else{
                        $to = $this->env("EMAIL_WEBSITE_ADMIN");
                        $from = $this->env("EMAIL_WEBSITE");
                        $subject = "Informacíon nueva de contácto";
                        $body = "
                                <p><b>Fecha:  ".date("Y-m-d H:i")."</b></p><p>Se ha registrado una nueva solicitud de contácto</p>\n
                                <dl>
                                    <dt><b>Nombre</b></dt>
                                    <dd>{$this->params["name_contact"]}</dd>
                                    <dt><b>Email</b></dt>
                                    <dd>{$this->params["email_contact"]}</dd>
                                </dl>\n
                                <p><b>Comentario</b></p>\n
                                <p>{$this->params["comment_contact"]}</p>
                                ";
                        $email = $this->sendEmail($to, $from, $subject, $body);
                        if($email){
                            return $this->response([$email],200, "Se envió el correo correctamente");
                        }else{
                            return $this->response([$email],400,"Error en el envío del formulario");
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