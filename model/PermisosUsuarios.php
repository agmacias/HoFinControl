<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Permisos
 *
 * @author Alejandro González Macías
 */
class PermisosUsuarios {

    private $id;
    private $usuario;
    private $permiso;
    private $nombre;

    public function __construct($id ,$usuario,$permiso,$nombre) {
        $this->id = $id;
        $this->usuario = $usuario;
        $this->permiso = $permiso;
        $this->nombre = $nombre;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    public function getPermiso() {
        return $this->permiso;
    }

    public function setPermiso($permiso) {
        $this->permiso = $permiso;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    
}
?>
