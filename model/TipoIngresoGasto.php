<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TipoIngresoGasto
 *
 * @author Administrador
 */
class TipoIngresoGasto {
    private $id;
    private $nombre;
    private $isIngreso;

    public function __construct($id ,$nombre,$isIngreso) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->isIngreso=$isIngreso;
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

    public function getIsIngreso() {
        return $this->isIngreso;
    }

    public function setIsIngreso($isIngreso) {
        $this->isIngreso = $isIngreso;
    }

    
}
?>
