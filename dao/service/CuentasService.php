<?php
require_once('../resources/CuentasDAO.php');

/*
	Leemos el flujo que hay en php://input
	Como codificamos los datos con JSON al enviarlos,
	al recibirlos tenemos que decodificarlos de la misma manera.
	Para ello, PHP provee el método json_decode: http://php.net/manual/es/function.json-decode.php
*/


$datos = json_decode(file_get_contents("php://input"));
$response = array();
$opcion = $datos->opcion;

if($opcion==1) {
    $user = $datos->usuario;
    $cuentas = CuentasDAO::getCuentasByUser($user);
    foreach ($cuentas as $k => $row) {
        $response[]=array("id"=>$row->getId(),"nombre"=>$row->getNombre(),
                "tipo_cuenta"=>$row->getTipo_cuenta(),
                "defecto"=>$row->getDefecto(),"cuantia"=>$row->getCuantia(),
                "fecha"=>$row->getFecha(),"f_fin"=>$row->getFechaFin(),
                "tipo_cuenta_id"=>$row->getTipo_cuenta_id());
    }
}else if($opcion==2) {
    // operación de alta
    $nombre = $datos->nombre;
    $tipo = $datos->tipo;
    $cuantia = $datos->cuantia;

    $timestamp = strtotime($datos->fecha);
    $fecha = date('Y-m-d H:i:s', $timestamp);

    $usuario = $datos->usuario;
    $defecto = $datos->defecto;

    if(isset($datos->fecha_fin)) {
        $timestamp = strtotime($datos->fecha_fin);
        $fecha_fin = date('Y-m-d H:i:s', $timestamp);
    }else {
        $fecha_fin = null;
    }

    $result = CuentasDAO::insertCuenta($nombre, $tipo, $usuario, $fecha, $cuantia, $defecto, $fecha_fin);

    if($result==true) {
        $response = array('response' => true, 'mensaje' => 'Cuenta dada de alta correctamente.');
    }else {
        $response = array('response' => true, 'mensaje' => 'Se ha producido un error en el guardado de datos.');
    }

}else if($opcion==3 || $opcion==4) {
    // operación de modificación

    $id= $datos->id;
    $nombre = $datos->nombre;
    $tipo = $datos->tipo;
    $cuantia = $datos->cuantia;

    $timestamp = strtotime($datos->fecha);
    $fecha = date('Y-m-d H:i:s', $timestamp);

    $usuario = $datos->usuario;
    $defecto = $datos->defecto;

    if(isset($datos->fecha_fin)) {
        $timestamp = strtotime($datos->fecha_fin);
        $fecha_fin = date('Y-m-d H:i:s', $timestamp);
    }else {
        $fecha_fin = null;
    }

    if($opcion==4){
        // actualizamos todas las cuentas a defecto=0
        $result = CuentasDAO::updateCuentaDefecto();
    }

    $result = CuentasDAO::updateCuentaById($id, $nombre, $tipo, $fecha, $cuantia, $defecto, $fecha_fin);

    if($result==true) {
        $response = array('response' => true, 'mensaje' => 'Operación realizada correctamente.');
    }else {
        $response = array('response' => true, 'mensaje' => 'Se ha producido un error en la operación.');
    }

}else {
    // operación de eliminación
    $id = $datos->id;
    $result = CuentasDAO::deleteCuentaById($id);

    if($result==true) {
        $response = array('response' => true, 'mensaje' => 'Cuenta eliminada correctamente.');
    }else {
        $response = array('response' => true, 'mensaje' => 'Se ha producido un error en la operación.');
    }
}

//no hacer return, sino echo
header('Content-Type: application/json');
echo json_encode($response);

?>
