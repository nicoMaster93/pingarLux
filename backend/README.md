Brenntag Backend
Este proyecto contiene el backend para Brenntag.

Instalación
Para instalar el proyecto, es necesario seguir los siguientes pasos:

Instalar Node.js localmente.

Instalar la dependencia apidoc de forma global utilizando el siguiente comando:

Copy
npm install apidoc -g

```

Agregar el archivo apiDoc.json en la raíz del proyecto, con la siguiente configuración:

json
Copy
{
  "name": "Brenntag Documentation",
  "version": "1.0.0",
  "description": "",
  "title": "Brenntag API",
  "url": "http://localhost/proyectsInHouse/brentage/backend/?",
  "sampleUrl": "http://localhost/proyectsInHouse/brentage/backend/?",
  "header": {
    "title": "Api Documentation",
    "content": "Documentacion de los servicios para el correcto funcionamiento de las plataformas de brenntag"
  },
  "footer": {
    "title": "",
    "content": ""
  },
  "template": {
    "forceLanguage": "es"
  },
  "debug": true,
  "markdown": true,
  "src": {
    "exclude": ["./vendor", "./tests"],
    "include": ["./services/**/*.php"]
  },
  "dest": "./docs"
}
```

En este archivo, se define el nombre y la versión de la documentación, así como la URL base de la API y la configuración de la generación de la documentación.

Ejecutar el siguiente comando en la terminal para generar la documentación:

scheme
Copy
apidoc -i ./services -o ./docs

```

Este comando indica a `apidoc` que genere la documentación a partir de los archivos PHP ubicados en el folder `services`, y que la guarde en el folder `docs`.

La opción `-i` se utiliza para indicar el folder de entrada y la opción `-o` para indicar el folder de salida.
Documentación
La documentación de la API se encuentra en el folder docs y puede ser vista abriendo el archivo index.html en un navegador web.

La documentación incluye información sobre los endpoints de la API, sus parámetros y respuestas, y ejemplos de uso. Además, se incluyen descripciones adicionales para los endpoints y los parámetros de entrada.
```

Ejemplo de como se debe documentar una funcion que se va exponer como un servicio en internet

```
  /**
    * @api {POST} endpoint=Publish&action=loguin Loguin
    * @apiName loguin
    * @apiGroup Users
    * @apiDescription Descripcion del servicio
    *
    * @apiHeader {String} key Description
    *
    * @apiParam {String} key Description
    *

    * @apiSuccessExample {json} Respuesta Exitosa:
    *     HTTP/1.1 200 OK
    *     {
    *        "code": 200,
    *        "message": "",
    *        "result": ["Se actualizó correctamente"]
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

```
