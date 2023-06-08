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
             * @api {GET} endpoint=Booking&action=getAllProfiles Obtener Equipo
             * @apiName Get Booking Team
             * @apiGroup Booking
             * @apiDescription Descripcion del servicio
             *
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
            
            /**
             * @api {GET} endpoint=Booking&action=getAllImages Obtener Imagenes
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
            public function getAllImages();
            /**
             * @api {POST} endpoint=Booking&action=checkAvailability disponibilidad de reservacion
             * @apiName Check Availability - Booking
             * @apiGroup Booking
             * @apiDescription valida la disponibilidad de la reservacion por fechas
             *
             * @apiParam {String} fechaIni Fecha Ingreso
             * @apiParam {String} fechaEnd Fecha Salida
             * @apiParam {Number} adults Adultos
             * @apiParam {Number} children Niños
             * @apiParam {String} email Correo del interesado
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
            public function checkAvailability();
            /**
             * @api {POST} endpoint=Booking&action=sendContactInformation Formulario Contácto 
             * @apiName sendContactInformation - Booking
             * @apiGroup Booking
             * @apiDescription Envia el formulario de contacto por correo
             *
             * @apiParam {String} name_contact Nombre Contacto
             * @apiParam {String} comment_contact Comentario Contacto
             * @apiParam {String} email_contact Correo Contacto
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
            public function sendContactInformation();
            
        }
         ?>