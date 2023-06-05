<?php 
        /**
         * Automatically generated script 
         * Creation Date: 2023-05-30 19:46:19 
         * Autor: Nicolas Hernandez 
         * version:1
         * Cliente:Pingar Lux
         * Informacion: 
         * Esta interfaz implementa los servicios que se exponen y la documentacion de cada uno usando la sintaxis de apiDoc
         * Url Documentation apiDoc : https://apidocjs.com
         **/
        
        interface BookingControllerInterfaces {
            /**
             * @api {GET} endpoint=Booking&action=getAllBooking Obtener
             * @apiName Get Booking
             * @apiGroup Booking
             * @apiDescription Descripcion del servicio
             *
             * @apiHeader {String} key Description
             *
             * @apiParam {String} key Description 
             * @apiParam {Numbre} key2 Description 
             *
             
             * @apiSuccessExample {json} Respuesta Exitosa:
             *     HTTP/1.1 200 OK
             *     {
             *        "code": 200,
             *        "message": "",
             *        "result": [...]
             *      }
             * 
             * @apiErrorExample {json} Respuesta de Error:
             *     HTTP/1.1 500 Internal Server Error
             *     {
             *        "code": 500,
             *        "message": "Error en el servidor.",
             *        "result": []
             *      }
            */
            public function getAllBooking();
            /**
             * @api {GET} endpoint=Booking&action=getAllProfiles Obtener Perfiles
             * @apiName Get Booking
             * @apiGroup Booking
             * @apiDescription Descripcion del servicio
             *
             * @apiHeader {String} key Description
             *
             *
             
             * @apiSuccessExample {json} Respuesta Exitosa:
             *     HTTP/1.1 200 OK
             *     {
             *        "code": 200,
             *        "message": "",
             *        "result": [...]
             *      }
             * 
             * @apiErrorExample {json} Respuesta de Error:
             *     HTTP/1.1 500 Internal Server Error
             *     {
             *        "code": 500,
             *        "message": "Error en el servidor.",
             *        "result": []
             *      }
            */
            public function getAllProfiles();
            
        }
         ?>