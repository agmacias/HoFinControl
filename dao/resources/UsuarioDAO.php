<?php

require_once '../DB.php';
require_once '../../model/Usuario.php';

/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of UsuarioDAO
 *
 * @author Administrador
 */
class UsuarioDAO {
    //put your code here

    // Método que averigua si el login es correcto. Devuelve el objeto usuario, si es correcto o null en caso contrario
    public static function getUsuarioExplicito($nombre, $contrasena) {
        $conexion = DB::getConnection();
        $usuario = null;
        try {
            $consulta = "SELECT * FROM usuarios WHERE user = :usuario ";
            if(isset($contrasena)){
                $consulta = $consulta . "AND password = md5(:contrasena)";
            }
            // Consulta preparada
            $sql = $conexion->prepare($consulta);
            $sql->bindParam(':usuario', $nombre, PDO::PARAM_STR);
            if(isset($contrasena)){
                $sql->bindParam(':contrasena', $contrasena, PDO::PARAM_STR);
            }

            if($sql->execute()) {
                while ($row = $sql->fetch()) {
                    /*
                     * Construimos el objeto usuario a través del método explicito ya que queremos un objeto cargado con los datos
                     * que acabamos de leer de la base de datos.
                    */
                    $usuario = Usuario::explicito($row['id'], $row['user'], $row['password'], $row['nombre'],$row['apellido1'],$row['apellido2']);
                }
            }


        } catch (PDOException $e) {
            echo "ERROR - No se pudo acceder a la tabla usuarios: " . $e->getMessage();
        }

        return $usuario;
    }

    // Método que averigua si el login es correcto. Devuelve el objeto usuario, si es correcto o null en caso contrario
    public static function getUsuario($user) {
        $conexion = DB::getConnection();
        $usuario = null;
        try {
            // Consulta preparada
            $sql = $conexion->prepare("SELECT * FROM usuarios WHERE user = :usuario");
            $sql->bindParam(':usuario', $user, PDO::PARAM_STR);

            if($sql->execute()) {
                while ($row = $sql->fetch()) {
                    /*
                     * Construimos el objeto usuario a través del método explicito ya que queremos un objeto cargado con los datos
                     * que acabamos de leer de la base de datos.
                    */
                    $usuario = new Usuario($row['id'], $row['user'], $row['password'], $row['nombre'], $row['apellido1'], $row['apellido2'], $row['f_nacimiento'], $row['pais'], $row['telefono'], $row['direccion'], $row['mail']);
                }
            }
        } catch (PDOException $e) {
            echo "ERROR - No se pudo acceder a la tabla usuarios: " . $e->getMessage();
        }

        return $usuario;
    }

    public static function updateUsuario($id,$usuario,$contrasenia,$nombre,$apellido1,$apellido2,$f_nacimiento,$pais,$telefono,$direccion,$email) {
        $prepareSql = "UPDATE usuarios SET user = ?, nombre=?, apellido1=?, apellido2=?, f_nacimiento=?, pais=?, telefono=?, direccion=?, mail=?";

        if(isset($contrasena)){
            $prepareSql = $prepareSql." , password=md5(?)";
        }

        $prepareSql = $prepareSql."where id=?;";

        try {
            // obtenemos la conexión y abrimos transacción
            $conexion = DB::getConnection();

            if(!isset($conexion)) {
                // si no hay conexión devolvemos null y no continuamos con la ejecución
                return null;
            }
            $conexion -> beginTransaction();
            // preparamos la consulta y establecemos los parámetros
            $consulta = $conexion->prepare($prepareSql);
            $consulta ->bindparam(1, $usuario);            
            $consulta ->bindparam(2, $nombre);
            $consulta ->bindparam(3, $apellido1);
            $consulta ->bindparam(4, $apellido2);
            $consulta ->bindparam(5, $f_nacimiento);
            $consulta ->bindparam(6, $pais);
            $consulta ->bindparam(7, $telefono);
            $consulta ->bindparam(8, $direccion);
            $consulta ->bindparam(9, $email);
            if(isset($contrasena)){
                $consulta ->bindparam(10, $contrasenia);
                $consulta ->bindparam(11, $id);
            }else{
                $consulta ->bindparam(10, $id);
            }



            // lanzamos la consulta, en caso de ir ok, lanzamos commit en caso contrario lanzamos rollback
            $ok = $consulta->execute();
            if($ok) {
                $conexion->commit();
                return true;
            }else {
                $conexion -> rollBack();
                return false;
            }
        }catch (PDOException $e) {
            $conexion -> rollBack();
            return false;
        }
    }

    public static function insertUsuario($usuario,$contrasenia,$nombre,$apellido1,$apellido2,$f_nacimiento,$pais,$telefono,$direccion,$email) {
        $passmd5 = md5($contrasenia);
        $prepareSql = "INSERT INTO usuarios (user, password, nombre, apellido1, apellido2, f_nacimiento, pais, telefono, direccion, mail) values (?,?,?,?,?,?,?,?,?,?);";

        try {
            // obtenemos la conexión y abrimos transacción
            $conexion = DB::getConnection();

            if(!isset($conexion)) {
                // si no hay conexión devolvemos null y no continuamos con la ejecución
                return null;
            }
            $conexion -> beginTransaction();
            // preparamos la consulta y establecemos los parámetros
            $consulta = $conexion->prepare($prepareSql);
            $consulta ->bindparam(1, $usuario);
            $consulta ->bindparam(2, $passmd5);
            $consulta ->bindparam(3, $nombre);
            $consulta ->bindparam(4, $apellido1);
            $consulta ->bindparam(5, $apellido2);
            $consulta ->bindparam(6, $f_nacimiento);
            $consulta ->bindparam(7, $pais);
            $consulta ->bindparam(8, $telefono);
            $consulta ->bindparam(9, $direccion);
            $consulta ->bindparam(10, $email);

            // lanzamos la consulta, en caso de ir ok, lanzamos commit en caso contrario lanzamos rollback
            $ok = $consulta->execute();
            if($ok) {
                $conexion->commit();
                return true;
            }else {
                $conexion -> rollBack();
                return false;
            }
        }catch (PDOException $e) {
            $conexion -> rollBack();
            return false;
        }
    }

    // lanza una consulta para obtener todos los usuarios dados de alta
    public static function getUsuariosByParams($usuario,$nombre,$apellido,$apellido2,$fechaDesde,$fechaHasta){
        $conexion = DB::getConnection();
        $usuarios = array();
        try {
            // Consulta preparada
            $consulta = "SELECT * FROM usuarios where id!=1 ";

            if(isset($usuario)){
                $consulta = $consulta." and user like :usuario ";
            }
            if(isset($nombre)){
                $consulta = $consulta." and nombre like :nombre";
            }
            if(isset($apellido)){
                $consulta = $consulta." and apellido_1 like :apellido";
            }
            if(isset($apellido2)){
                $consulta = $consulta." and apellido_2 like :apellido2'";
            }            
            if(isset($fechaDesde)){
                $consulta = $consulta." and f_nacimiento >= :fechaDesde ";
            }
            if(isset($fechaHasta)){
                $consulta = $consulta." and f_nacimiento <= :fechaHasta ";
            }

            $sql = $conexion->prepare($consulta);
            
            if(isset($usuario)){
               $usuario = "%".$usuario."%";
               $sql->bindParam(':usuario', $usuario, PDO::PARAM_STR);
            }
            if(isset($nombre)){
               $nombre = "%".$nombre."%";
               $sql->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            }
            if(isset($apellido)){
               $usuario = "%".$apellido."%";
               $sql->bindParam(':apellido1', $apellido, PDO::PARAM_STR);
            }
            if(isset($apellido2)){
               $apellido2 = "%".$apellido2."%";
               $sql->bindParam(':apellido2', $apellido2, PDO::PARAM_STR);
            }
            if(isset($fechaDesde)){
               $sql->bindParam(':fechaDesde', $fechaDesde, PDO::PARAM_STR);
            }
            if(isset($fechaHasta)){
               $sql->bindParam(':fechaHasta', $fechaHasta, PDO::PARAM_STR);
            }

            if($sql->execute()) {
                while ($row = $sql->fetch()) {
                    /*
                     * Construimos el objeto usuario a través del método explicito ya que queremos un objeto cargado con los datos
                     * que acabamos de leer de la base de datos.
                    */
                    $usuarios[] = new Usuario($row['id'], $row['user'], $row['password'], $row['nombre'], $row['apellido1'], $row['apellido2'], $row['f_nacimiento'], $row['pais'], $row['telefono'], $row['direccion'], $row['mail']);
                }
            }
        } catch (PDOException $e) {
            echo "ERROR - No se pudo acceder a la tabla usuarios: " . $e->getMessage();
        }

        return $usuarios;
    }
}
?>
