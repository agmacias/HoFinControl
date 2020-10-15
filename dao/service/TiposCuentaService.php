<?php

require_once('../resources/TiposCuentasDAO.php');

$datos = json_decode(file_get_contents("php://input"));

$respuesta=array();
$tiposCuentas = TiposCuentasDAO::getTiposCuentas();
foreach ($tiposCuentas as $k => $row) {
    $respuesta[]=array("id"=>$row->getId(),"nombre"=>$row->getNombre());
}

//no hacer return, sino echo
header('Content-Type: application/json');
echo json_encode($respuesta);

?>
