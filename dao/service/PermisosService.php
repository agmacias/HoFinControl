<?php

require_once('../resources/PermisosDAO.php');

/*
	Leemos el flujo que hay en php://input
	Como codificamos los datos con JSON al enviarlos,
	al recibirlos tenemos que decodificarlos de la misma manera.
	Para ello, PHP provee el método json_decode: http://php.net/manual/es/function.json-decode.php
*/


$datos = json_decode(file_get_contents("php://input"));
$response = array();
$opcion = $datos->opcion;

if($opcion==1){
    // obtenemos los permisos asociados al usuario
    $user = $datos->usuario;

    $permisos = PermisosDAO::getPermisosByUsuario($user);

    foreach ($permisos as $k => $row) {
        $response[]=array("id"=>$row->getId(),"usuario"=>$row->getUsuario(),
                "permiso"=>$row->getPermiso(),"nombre"=>$row->getNombre());
    }

}else if($opcion==2){
    // obtenemos todos los permisos disponibles

    $permisos = PermisosDAO::getAllPermisos();

    foreach ($permisos as $k => $row) {
        $response[]=array("id"=>$row->getId(),"nombre"=>$row->getNombre());
    }
}else if($opcion==3){
    // guardamos los permisos

    $user = $datos->usuario;
    $permisos = $datos->permisos;
    // primero antes de nada eliminamos todos los permisos que tenga asociados el usuario.
    $delete = PermisosDAO::deleteAllPermisosUsuario($user);
   
    foreach ($permisos as $k => $row) {
        foreach ($row as $i => $propiedad) {
            if($i=='id'){
                $result = PermisosDAO::insertPermisos($user, $propiedad);
                if($result==true) {
                    $response = array('response' => true, 'mensaje' => 'Permisos asociados correctamente.');
                }else {
                    break;
                    $response = array('response' => false, 'mensaje' => 'Se ha producido un error en el guardado de datos.');
                }
            }
        }
    }
}

//no hacer return, sino echo
header('Content-Type: application/json');
echo json_encode($response);

?>
