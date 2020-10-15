<?php
/**
 * Description of Cuentas
 *
 * @author Administrador
 */
class Cuentas {
    private $id;
    private $nombre;
    private $tipo_cuenta_id;
    private $tipo_cuenta;
    private $usuario;
    private $fecha;
    private $cuantia;
    private $defecto;
    private $fechaFin;

    public function __construct($id ,$nombre, $tipo_cuenta, $usuario, $fecha,
            $cuantia, $defecto, $fechaFin, $tipo_cuenta_id) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->tipo_cuenta=$tipo_cuenta;
        $this->usuario = $usuario;
        $this->fecha = $fecha;
        $this->cuantia = $cuantia;
        $this->defecto = $defecto;
        $this->fechaFin = $fechaFin;
        $this->tipo_cuenta_id = $tipo_cuenta_id;
    }

    public function getTipo_cuenta_id() {
        return $this->tipo_cuenta_id;
    }

    public function setTipo_cuenta_id($tipo_cuenta_id) {
        $this->tipo_cuenta_id = $tipo_cuenta_id;
    }

    public function getFechaFin() {
        return $this->fechaFin;
    }

    public function setFechaFin($fechaFin) {
        $this->fechaFin = $fechaFin;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getTipo_cuenta() {
        return $this->tipo_cuenta;
    }

    public function setTipo_cuenta($tipo_cuenta) {
        $this->tipo_cuenta = $tipo_cuenta;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    public function getCuantia() {
        return $this->cuantia;
    }

    public function setCuantia($cuantia) {
        $this->cuantia = $cuantia;
    }

    public function getDefecto() {
        return $this->defecto;
    }

    public function setDefecto($defecto) {
        $this->defecto = $defecto;
    }


}
?>
