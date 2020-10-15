<?php

require_once('../resources/IngresoGastoDAO.php');

$datos = json_decode(file_get_contents("php://input"));

$isIngreso = $datos->esIngreso;

$respuesta=array();
$ingresoGasto = IngresoGastoDAO::getTipoIngresoGasto($isIngreso);
foreach ($ingresoGasto as $k => $row) {
    $respuesta[]=array("id"=>$row->getId(),"nombre"=>$row->getNombre());
}

//no hacer return, sino echo
header('Content-Type: application/json');
echo json_encode($respuesta);

?>