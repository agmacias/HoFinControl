<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Transacciones
 *
 * @author Administrador
 */
class Transacciones {
    //put your code here

    private $id;    
    private $tipoId;
    private $tipoNombre;
    private $fecha;
    private $asunto;
    private $descripcion;
    private $usuario;
    private $cuenta;
    private $nombreCuenta;
    private $cuantia;

    public function __construct($id , $tipoId, $tipoNombre, $fecha, $asunto, $descripcion, $usuario, $nombre_cuenta, $cuenta, $cuantia) {
        $this->id = $id;        
        $this->tipoId = $tipoId;
        $this->tipoNombre = $tipoNombre;
        $this->fecha = $fecha;
        $this->asunto = $asunto;
        $this->descripcion = $descripcion;
        $this->usuario = $usuario;
        $this->nombreCuenta=$nombre_cuenta;
        $this->cuenta=$cuenta;
        $this->cuantia=$cuantia;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTipoId() {
        return $this->tipoId;
    }

    public function setTipoId($tipoId) {
        $this->tipoId = $tipoId;
    }

    public function getTipoNombre() {
        return $this->tipoNombre;
    }

    public function setTipoNombre($tipoNombre) {
        $this->tipoNombre = $tipoNombre;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    public function getAsunto() {
        return $this->asunto;
    }

    public function setAsunto($asunto) {
        $this->asunto = $asunto;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    public function getNombreCuenta() {
        return $this->nombreCuenta;
    }

    public function setNombreCuenta($nombreCuenta) {
        $this->nombreCuenta = $nombreCuenta;
    }

    public function getCuenta() {
        return $this->cuenta;
    }

    public function setCuenta($cuenta) {
        $this->cuenta = $cuenta;
    }

    public function getCuantia() {
        return $this->cuantia;
    }

    public function setCuantia($cuantia) {
        $this->cuantia = $cuantia;
    }



}
?>
