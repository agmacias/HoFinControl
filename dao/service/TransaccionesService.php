<?php
require_once('../resources/TransaccionesDAO.php');

/*
	Leemos el flujo que hay en php://input
	Como codificamos los datos con JSON al enviarlos,
	al recibirlos tenemos que decodificarlos de la misma manera.
	Para ello, PHP provee el método json_decode: http://php.net/manual/es/function.json-decode.php
*/


$datos = json_decode(file_get_contents("php://input"));
$response = array();
$opcion = $datos->opcion;

if($opcion==0 || $opcion==8) {
    $user = $datos->usuario;
    $cuenta = null;
    $tipo = null;
    $fechaDesde = null;
    $fechaHasta = null;
    $asunto = null;
    $limit = null;
    $isIngreso = null;
    $groupBy = null;

    if($opcion==8){
        // si opcion es igual a 8 los atributos cuenta y tipo vendrán como array
        if(isset($datos->cuenta)){
            $cuenta = 0;
            foreach ($datos->cuenta as $k => $row) {
                $cuenta = $cuenta.",".$row;
            }
        }
        if(isset($datos->tipo)){
            $tipo = 0;
            foreach ($datos->tipo as $k => $row) {
                $tipo = $tipo.",".$row;
            }
        }
    }else{
        if(isset($datos->cuenta)){
            $cuenta = $datos->cuenta;
        }
        if(isset($datos->tipo)){
            $tipo = $datos->tipo;
        }
    }
    
    if(isset($datos->fechaDesde)){
        $fechaDesde = $datos->fechaDesde;
    }
    if(isset($datos->fechaHasta)){
        $fechaHasta = $datos->fechaHasta;
    }
    if(isset($datos->asunto)){
        $asunto = $datos->asunto;
    }
    if(isset($datos->isIngreso)){
        $isIngreso = $datos->isIngreso;
    }
    if(isset($datos->groupBy)){
        $groupBy = $datos->groupBy;
    }
    if(isset($datos->limit)){
        $limit = $datos->limit;
    }
    
    $cuentas = TransaccionesDAO::getTransaccionesByParams($user, $cuenta, $tipo, $fechaDesde, $fechaHasta, $asunto, $isIngreso, $groupBy, $limit);
    foreach ($cuentas as $k => $row) {
        $response[]=array("id"=>$row->getId(),"tipo"=>$row->getTipoId(),
                "tipo_nombre"=>$row->getTipoNombre(),
                "fecha"=>$row->getFecha(),"usuario"=>$row->getUsuario(),
                "asunto"=>$row->getAsunto(),"descripcion"=>$row->getDescripcion(),
                "cuenta"=>$row->getCuenta(), "nombre_cuenta"=>$row->getNombreCuenta(),
                "cuantia"=>$row->getCuantia());
    }
}
else if($opcion==1) {
    $user = $datos->usuario;
    $tipoIngreso = $datos->tipoIngreso;
    $cuentas = TransaccionesDAO::getTransaccionesByUser($user,$tipoIngreso);
    foreach ($cuentas as $k => $row) {
        $response[]=array("id"=>$row->getId(),"tipo"=>$row->getTipoId(),
                "tipo_nombre"=>$row->getTipoNombre(),
                "fecha"=>$row->getFecha(),"usuario"=>$row->getUsuario(),
                "asunto"=>$row->getAsunto(),"descripcion"=>$row->getDescripcion(),
                "cuenta"=>$row->getCuenta(),
                "nombre_cuenta"=>$row->getNombreCuenta(),
                "cuantia"=>$row->getCuantia());
    }
}else if($opcion==2) {
    // operación de alta
    $cuenta = $datos->cuenta;
    $tipo = $datos->tipo;
    $usuario = $datos->usuario;

    $timestamp = strtotime($datos->fecha);
    $fecha = date('Y-m-d H:i:s', $timestamp);

    $asunto = $datos->asunto;
    $cuantia = $datos->cuantia;
    if(isset($datos->descripcion)){
        $descripcion = $datos->descripcion;
    }else{
        $descripcion = null;
    }
    
    $result = TransaccionesDAO::insertTransaccion($cuenta, $tipo, $usuario, $fecha, $asunto, $descripcion, $cuantia);

    if($result==true) {
        $response = array('response' => true, 'mensaje' => 'Transacción dada de alta correctamente.');
    }else {
        $response = array('response' => false, 'mensaje' => 'Se ha producido un error en el guardado de datos.');
    }
}else if($opcion == 3){
    // operación de modificación

    $id = $datos->id;
    $cuenta = $datos->cuenta;
    $tipo = $datos->tipo;
    $usuario = $datos->usuario;

    $timestamp = strtotime($datos->fecha);
    $fecha = date('Y-m-d H:i:s', $timestamp);

    $asunto = $datos->asunto;
    $cuantia = $datos->cuantia;
    if(isset($datos->descripcion)){
        $descripcion = $datos->descripcion;
    }else{
        $descripcion = null;
    }

    $result = TransaccionesDAO::updateTransaccion($id, $cuenta, $tipo, $usuario, $fecha, $asunto, $descripcion, $cuantia);

    if($result==true) {
        $response = array('response' => true, 'mensaje' => 'Transacción actualizada correctamente.');
    }else {
        $response = array('response' => false, 'mensaje' => 'Se ha producido un error en el guardado de datos.');
    }
}else if($opcion==4){
    // eliminar
    $id = $datos->id;

    $result = TransaccionesDAO::deleteTransaccion($id);

    if($result==true) {
        $response = array('response' => true, 'mensaje' => 'Transacción eliminada correctamente.');
    }else {
        $response = array('response' => false, 'mensaje' => 'Se ha producido un error en la operación.');
    }
}else if($opcion==5){
    // obtener el total de las transacciones
    $user = $datos->usuario;
    $cuenta = $datos->cuenta;

    $result = TransaccionesDAO::getTotalesTransaccionesByUserAndCuenta($user,$cuenta);

    $arrayResult= array();
    foreach ($result as $k => $row) {
        $arrayResult[$k] = $row;
    }

    $response[] = $arrayResult;
}else if($opcion==6 || $opcion==7){
    // obtener las transacciones por año
    $user = $datos->usuario;
    $anio = $datos->anio;    
    $cuenta = $datos->cuenta;

    if($opcion==6){
        $response = TransaccionesDAO::getTransaccionesByYearUserCeuntaAndTipo($anio, $user, $cuenta);
    }else{
        $response = TransaccionesDAO::getGastosByUserYearAndCount($user, $cuenta, $anio);
    }
}

//no hacer return, sino echo
header('Content-Type: application/json');
echo json_encode($response);

?>
