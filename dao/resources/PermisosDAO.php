<?php
require_once '../DB.php';
require_once '../../model/Permisos.php';
require_once '../../model/PermisosUsuarios.php';
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PermisosDAO
 *
 * @author Administrador
 */
class PermisosDAO {

    // función para obtener todos los permisos disponibles
    public static function getAllPermisos(){
        $conexion = DB::getConnection();
        $permisos = array();
        try {
            // Consulta preparada
            $sql = $conexion->prepare("select * from permisos;");            

            if($sql->execute()) {
                while ($row = $sql->fetch()) {
                    
                    $permisos[] = new Permisos($row['id'], $row['nombre']);
                }
            }

        } catch (PDOException $e) {
            echo "ERROR - No se pudo acceder a la tabla permisos: " . $e->getMessage();
        }

        return $permisos;
    }

    // función para obtener todos los permisos asociados a un usuario.
    public static function getPermisosByUsuario($user){        
        $conexion = DB::getConnection();
        $permisos = array();
        try {
            // Consulta preparada
            $sql = $conexion->prepare("select perusu.id , perusu.usuario, perusu.permiso, per.nombre from permisos_usuarios perusu inner join permisos per on perusu.permiso = per.id and perusu.usuario = :usuario");
            $sql->bindParam(':usuario', $user, PDO::PARAM_INT);

            if($sql->execute()) {
                while ($row = $sql->fetch()) {
                    /*
                     * Construimos el objeto usuario a través del método explicito ya que queremos un objeto cargado con los datos
                     * que acabamos de leer de la base de datos.
                    */
                    $permisos[] = new PermisosUsuarios($row['id'], $row['usuario'],$row['permiso'],$row['nombre']);
                }
            }

        } catch (PDOException $e) {
            echo "ERROR - No se pudo acceder a la tabla permisos_usuarios: " . $e->getMessage();
        }

        return $permisos;

    }

    // función para eliminar todos los permisos de un usuario
    public static function deleteAllPermisosUsuario($idUsuario){
        $sql = "DELETE from permisos_usuarios where usuario=".$idUsuario;

        try {
            // obtenemos la conexión y abrimos transacción
            $conexion = DB::getConnection();

            if(!isset($conexion)) {
                // si no hay conexión devolvemos null y no continuamos con la ejecución
                return null;
            }
            $conexion -> beginTransaction();
            // preparamos la consulta y establecemos los parámetros
            $consulta = $conexion->prepare($sql);            
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

    // inserta un permiso para un usuario
    public static function insertPermisos($idUsuario, $idPermiso){
        $sql = "INSERT INTO permisos_usuarios (usuario,permiso) values (?,?)";

        try {
            // obtenemos la conexión y abrimos transacción
            $conexion = DB::getConnection();

            if(!isset($conexion)) {
                // si no hay conexión devolvemos null y no continuamos con la ejecución
                return null;
            }
            $conexion -> beginTransaction();
            // preparamos la consulta y establecemos los parámetros
            $consulta = $conexion->prepare($sql);
            $consulta ->bindparam(1, $idUsuario);
            $consulta ->bindparam(2, $idPermiso);
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
