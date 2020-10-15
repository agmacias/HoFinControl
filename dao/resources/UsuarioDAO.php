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
            // Consulta preparada
            $sql = $conexion->prepare("SELECT * FROM usuarios WHERE user = :usuario AND password = md5(:contrasena)");
            $sql->bindParam(':usuario', $nombre, PDO::PARAM_STR);
            $sql->bindParam(':contrasena', $contrasena, PDO::PARAM_STR);

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
        $prepareSql = "UPDATE usuarios SET user = ?, password=md5(?), nombre=?, apellido1=?, apellido2=?, f_nacimiento=?, pais=?, telefono=?, direccion=?, mail=? where id=?;";

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
            $consulta ->bindparam(2, $contrasenia);
            $consulta ->bindparam(3, $nombre);
            $consulta ->bindparam(4, $apellido1);
            $consulta ->bindparam(5, $apellido2);
            $consulta ->bindparam(6, $f_nacimiento);
            $consulta ->bindparam(7, $pais);
            $consulta ->bindparam(8, $telefono);
            $consulta ->bindparam(9, $direccion);
            $consulta ->bindparam(10, $email);
            $consulta ->bindparam(11, $id);


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
}
?>
