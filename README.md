# API REST

#### ¿Qué es un API y para que sirve?

API: Application Programming Interface, conjunto de reglas que definen como se van a comunicar dos aplicaciones, por ejemplo qué tipos de request y response se van a utilizar.

En **POO** una API son todos los elemento públicos que están disponibles.

## Conocer conceptos principales de Rest

#### ¿Qué es y cómo funciona el protocolo HTTP?

HTTP: Hytpertext Transfer Protocol, conjunto de reglas en las que se van a comunicar dos entidades.

Protocolo: conjunto de reglas.

#### ¿Qué significa Rest?

REST: Representational State Transfer o bien transferencia de estado representacional.

API RESTful es una API diseñada con los conceptos de REST.
* Recurso: todo dentro de una Api RESTFul debe ser un recurso.
* URI: Todos los recursos se manipulan a partir de una URL (Identificadores Universales de Recursos).
* Acction: todas las peticiones de un Api Rest debe de estar asociado con un verbo HTTP:
  * Get: para obtener recursos.
  * Post: para escribir un recurso.
  * Put: Para editar un recurso.
  * Delete: para eliminar un recurso.

#### Métodos de autenticación 
#### HTTP
* Mediante HTTP auth user, debe de ir en el encabezado authorization.

#### HMAC
* HMAC: (Hash Message Authorizarion Code) para lo cual se necesita 3 elementos, Basado en Hash de Mensajes:
  * Función hash: Difícil de romper y que sea conocida por el cliente y el servidor.
  * Clave Secreta: para corroborar el HASH
  * UID: Id de usuario será utilizado dentro del HASH junto con la clave secreta y el timestamp

Es mucho más seguro que la autenticación vía HTTP.

#### Acceso mediante Tokens
Roles del servidores:
* Uno se encarga de la autenticación.
* Otro se encarga de desplegar los recursos del API.

Flujo.
* Petición para la solicitud de Token
* El servidor devuelve token
* El usuario hace una petición para pedir recurso.
* El servidor con los recursos hace una petición al servidor de autenticación para verificar token sea valido.
* Una vez verificado el token, el servidor devuelve los recursos al cliente.


## Manejo de Errores en servicio REST

Para las respuestas con error se utilizaran los siguientes códigos:
en los encabezados HTTP.
* 400: Bad Request, que el servidor no puede procesar la petición.
* 404: Not Found, el servidor no encuentra el recurso buscado.
* 500 Invernal Error Server, la petición no se pudo procesar por un error en el servidor.

## Buenas prácticas
* Utilizar sustantivos para nombrar los recursos.
* Añadir los nombres en plural para las URLs
* Las modificaciones se deben de hacer con su verbo HTTP correspondiente.
* Para devolver recursos asociados se debe incorporar sub-recursos
~~~
autos/1/choferes
~~~
* Navegabilida vía vínculos
* Cuando devuelva colecciones deben de ser filtrables, ordenables y paginables.
* Versionar las api
~~~
v1/autos/1/choferes
~~~

## LEVANTAR UN SERVIDOR PHP

`php -S localhost:8000 router.php`

## PROBAR EL SERVIDOR

`curl http://localhost:8000 -v`

## SOLICITAR LA COLECCIÓN COMPLETA DE LIBROS

`curl http://localhost:8000/books`

## SOLICITAR UN RECURSO ESPECÍFICO DE LA COLECCIÓN

`curl http://localhost:8000/books/1`

## AGREGAR UN NUEVO RECURSO A TRAVES DE POST

`curl -X 'POST' http://localhost:8000/books -d '{"titulo": "La quinta montaña", "id_autor": 3, "id_genero": 2}'`

## MODIFICAR UN RECURSO A TRAVES DE PUT

`curl -X 'PUT' http://localhost:8000/books/1 -d '{"titulo": "NUEVO TITULO", "id_autor": 3, "id_genero": 2}'`

## ELIMINAR UN RECURSO A TRAVES DE DELETE

`curl -X 'DELETE' http://localhost:8000/books/1`

# METODOS DE AUTENTICACIÓN REST

## AUTENTICACION VIA HTTP

`curl http://user:password@localhost:8000/books`

### POCO SEGURA

La credenciales se envian en cada Request y éstas viajan por la URL. Es ineficiente ya que la autenticación se vuelve a realizar en cada Request

## AUTENTICACION VIA HMAC

Codigo de autorización basado en un HASH de mensaje

Generar la marca de tiempo y el token de autenticacion mediante el script `generate_hash.php`

`php ./generate_hash.php 1`

Enviar los datos de autenticación para solicitar el acceso a los recursos

`curl http://localhost:8000/books -H 'X-HASH: 8ed2e41755213a314b27a67de8690c82671cfcc7' -H 'X-UID: 1' -H 'X-TIMESTAMP: 1585674534'`

Este método de autenticación es mas segura que la HTTP y se recomienda su implementación cuando la información que se envía no es muy sensible.

## ACCESS TOKENS

Extemadamente segura, éste método es utilizada para casos de información muy sensible.

Levantar el servidor de autenticación

`php -S localhost:8001 auth_server.php`

Solicitar el token de autenticación al servidor de autenticación

`curl http://localhost:8001 -X 'POST' -H 'X-Client-Id: 1' -H '-X-Secret:SuperSecreto!'`

Solicitar los recursos al servidor pasando el token proveído por el servidor de autenticación

`curl http://localhost:8000/books -H 'X-Token: [token generado]'`
