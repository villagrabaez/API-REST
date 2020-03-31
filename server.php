<?php

// Autenticación via HTTP

// // obteniendo los datos de autenticacion del usuario
// $user = array_key_exists('PHP_AUTH_USER', $_SERVER) ? $_SERVER['PHP_AUTH_USER'] : '';
// $pwd = array_key_exists('PHP_AUTH_PW', $_SERVER) ? $_SERVER['PHP_AUTH_PW'] : '';

// // var_dump($pwd);

// if ( $user !== 'bernardino' || $pwd !== '1234' ) {
//   //echo 'Inicie sesión';
//   die;
// }


// Autenticacion via HMAC

// if ( !array_key_exists('HTTP_X_HASH', $_SERVER) || !array_key_exists('HTTP_X_TIMESTAMP', $_SERVER) || !array_key_exists('HTTP_X_UID', $_SERVER) ) {
//   die;
// } 

// list($hash, $uid, $timestamp) = [
//   $_SERVER['HTTP_X_HASH'],
//   $_SERVER['HTTP_X_UID'],
//   $_SERVER['HTTP_X_TIMESTAMP'],
// ];

// $secret = 'Sh!! No se lo cuentes a nadie!';

// $newHash = sha1($uid.$timestamp.$secret);

// if ($newHash !== $hash) {
//   die;
// } 

// Autenticación via ACCESS TOKENS

// if ( !array_key_exists( 'HTTP_X_TOKEN', $_SESSION ) ) {
//   die;
// }

// $url = 'http://localhost:8001';

// $ch = curl_init( $url );
// curl_setopt( 
//   $ch, 
//   CURLOPT_HTTPHEADER, 
//   [
//     "X-Token: {$_SERVER['HTTP_X_TOKEN']}",
//   ]
// );

// curl_setopt(
//   $ch,
//   CURLOPT_RETURNTRANSFER,
//   true
// );

// $ret = curl_exec( $ch );

// if ( $ret !== 'true' ) {
//   die;
// }

// // Definimos los recursos disponibles
// $allowedResourceTypes = [
//   'books',
//   'authors',
//   'genres',
// ];

// // Validamos que el recurso este disponible
// $resourceType = $_GET['resource_type'];

// if ( !in_array($resourceType, $allowedResourceTypes) ) {
//   die;
// }

// Definimos los recursos

$books = [
  1 => [
    'titulo' => 'Lo que el viento se llevo',
    'id_autor' => 2,
    'id_genero' => 2,
  ],
  2 => [
    'titulo' => 'Viaje al centro de la tierra',
    'id_autor' => 3,
    'id_genero' => 2,
  ],
  3 => [
    'titulo' => 'El alquimista',
    'id_autor' => 4,
    'id_genero' => 2,
  ]
];

header('Content-Type: application/json');

// Verificamos si existe la key en el array y levantamos el id del recurso buscado en caso de que venga en la peticion GET
$resourceId = array_key_exists('resource_id', $_GET) ? $_GET['resource_id'] : '';

// Generamos la respuesta, asumiendo que el pedido es correcto
switch( strtoupper($_SERVER['REQUEST_METHOD']) ) {
  case 'GET':
    if ( empty($resourceId) ) {
      echo json_encode($books);
    } else {
      if (array_key_exists($resourceId, $books)) {
        echo json_encode($books[$resourceId]);
      }
    }
    break;
  case 'POST':
    $json = file_get_contents('php://input');
    $books[] = json_decode( $json, true );
    //echo array_keys($books)[count($books) -1 ];
    echo json_encode( $books );
    break;
  case 'PUT':
    // validamos que el recurso buscado exista
    if (!empty($resourceId) && array_key_exists($resourceId, $books)) {
      // tomamos la entrada cruda
      $json = file_get_contents('php://input');

      // tranformamos el json recibido aun nuevo elemento
      $books[ $resourceId ] = json_decode( $json, true );

      // retornamos la colección modificada
      echo json_encode( $books );
    }
    break;
  case 'DELETE':
    // validamos que el recurso buscado exista
    if (!empty($resourceId) && array_key_exists($resourceId, $books)) {
      // eliminamos el recurso de la colección
      unset( $books[$resourceId] );
    }
    echo json_encode( $books );
    break;
  default:
    break;
};
