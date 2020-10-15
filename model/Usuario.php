<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of Usuario
 *
 * @author Administrador
 */
class Usuario implements Serializable {
    private $id;
    private $usuario;
    private $password;
    private $nombre;
    private $apellido1;
    private $apellido2;
    private $f_nacimiento;
    private $pais;
    private $telefono;
    private $direccion;
    private $mail;

    /*
     * Una de las desventajas en PHP es que no permite los métodos sobrecargados y evidentemente los constructores. Pero hay una forma
     * elegante de simularlo con métodos static y el operador self().
     * En esta tarea quiero crear el objeto usuario de dos formas diferentes: una sin parámetros y la otra forma pasando todos sus datos.
     * Esto en Java, C++ , C# es fácil haces dos constructores, pero en PHP5 eso no está permitido. Y la manera de simularlo es con métodos
     * static que me retornen el objeto. Como se puede observar en el código anterior el constructor es private lo que significa que no puedo
     * acceder a el cuando creo el objeto.
    */
    static function implicito() {
        //return new self(NULL, NULL, NULL, NULL, NULL, NULL,NULL,NULL,NULL,NULL,NULL);
        return new Usuario(null, null, null, null, null, null, null, null, null, null, null);
    }

    static function explicito($id, $usuario, $password, $nombre,$apellido1,$apellido2) {
        return new Usuario($id, $usuario, $password, $nombre, $apellido1, $apellido2, null, null, null, null, null);
        //return new self($id, $usuario, $password, $nombre,$apellido1,$apellido2,NULL,NULL,NULL,NULL,NULL);
    }

    public function __construct($id ,$usuario, $password, $nombre, $apellido1,$apellido2,$f_nacimiento,$pais,$telefono,$direccion,$mail) {
        $this->id = $id;
        $this->usuario = $usuario;
        $this->password = $password;
        $this->nombre = $nombre;
        $this->apellido1= $apellido1;
        $this->apellido2= $apellido2;
        $this->f_nacimiento=$f_nacimiento;
        $this->pais=$pais;
        $this->telefono=$telefono;
        $this->direccion=$direccion;
        $this->mail=$mail;
    }

    function getUsuario() {
        return $this->usuario;
    }

    function getPassword() {
        return $this->password;
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

    public function getApellido1() {
        return $this->apellido1;
    }

    public function setApellido1($apellido1) {
        $this->apellido1 = $apellido1;
    }

    public function getApellido2() {
        return $this->apellido2;
    }

    public function setApellido2($apellido2) {
        $this->apellido2 = $apellido2;
    }

    public function getF_nacimiento() {
        return $this->f_nacimiento;
    }

    public function setF_nacimiento($f_nacimiento) {
        $this->f_nacimiento = $f_nacimiento;
    }

    public function getPais() {
        return $this->pais;
    }

    public function setPais($pais) {
        $this->pais = $pais;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    public function getMail() {
        return $this->mail;
    }

    public function setMail($mail) {
        $this->mail = $mail;
    }


    /*
     * Función que devuelve los el valor de los atributos serializados.
     * Esto es útil para el almacenamiento de valores en PHP sin perder su tipo y estructura.
    */

    public function serialize() {
        return serialize([$this->id, $this->usuario, $this->password, $this->nombre, $this->apellido1, $this->apellido2]);
    }

    /*
     * Crea un valor PHP a partir de una representación almacenada, es decir, crea un objeto de la clase
    */

    public function unserialize($serialized) {
        list(
                $this->id,
                $this->usuario,
                $this->password,
                $this->nombre,
                $this->apellido1,
                $this->apellido2
                ) = unserialize($serialized);
    }
}
