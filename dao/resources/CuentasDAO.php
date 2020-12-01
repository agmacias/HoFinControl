<?php
require_once '../DB.php';
require_once '../../model/Cuentas.php';
/**
 * Description of CuentasDAO
 *
 * @author Administrador
 */
class CuentasDAO {

    /*
     * Obtiene un listado de todas los registros de la tabla cuentas según un usuario
    */
    public static function getCuentasByUser($user) {
        $conexion = DB::getConnection();
        $cuentas = array();
        try {
            // Consulta preparada
            $sql = $conexion->prepare("SELECT cuentas.*, DATE_FORMAT(cuentas.fecha,'%d-%m-%Y') as fecha FROM cuentas where (cuentas.fecha_fin is null or cuentas.fecha_fin>sysdate()) and usuario='".$user."';");

            if($sql->execute()) {
                while ($row = $sql->fetch()) {
                    /*
                     * Construimos el objeto usuario a través del método explicito ya que queremos un objeto cargado con los datos
                     * que acabamos de leer de la base de datos.
                    */
                    $cuentas[] = new Cuentas($row['id'], $row['nombre'], $row['usuario'], $row['fecha'], $row['cuantia'], $row['defecto'], $row['fecha_fin']);
                }
            }


        } catch (PDOException $e) {
            echo "ERROR - No se pudo acceder a la tabla paises: " . $e->getMessage();
        }

        return $cuentas;
    }

    // Obtiene una cuenta por su Id
    public static function getCuentaById($id) {
        $conexion = DB::getConnection();
        $cuentas = array();
        try {
            // Consulta preparada
            $sql = $conexion->prepare("SELECT * FROM cuentas");

            if($sql->execute()) {
                while ($row = $sql->fetch()) {
                    /*
                     * Construimos el objeto usuario a través del método explicito ya que queremos un objeto cargado con los datos
                     * que acabamos de leer de la base de datos.
                    */
                    $cuentas[] = new Cuentas($row['id'], $row['nombre'], $row['usuario'], $row['fecha'], $row['cuantia'], $row['defecto']);
                }
            }


        } catch (PDOException $e) {
            echo "ERROR - No se pudo acceder a la tabla paises: " . $e->getMessage();
        }

        return $cuentas;
    }

    // Inserta una cuenta según los parámetros pasados
    public static function insertCuenta($nombre,$usuario,$fecha,$cuantia,$defecto,$f_fin) {
        $prepareSql = "INSERT into cuentas (nombre,usuario,fecha,cuantia,defecto,fecha_fin) VALUES (?,?,?,?,?,?);";

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
            $consulta ->bindparam(1, $nombre);            
            $consulta ->bindparam(2, $usuario);
            $consulta ->bindparam(3, $fecha);
            $consulta ->bindparam(4, $cuantia);
            $consulta ->bindparam(5, $defecto);
            $consulta ->bindparam(6, $f_fin);

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

    // consulta que actualizará todas las cuentas a defecto 0
    public static function updateCuentaDefecto() {
        $prepareSql = "UPDATE cuentas set defecto=0 where id != 0;";

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

    public static function updateCuentaById($id,$nombre,$fecha,$cuantia,$defecto,$f_fin) {
        $prepareSql = "UPDATE cuentas set nombre=?,fecha=?, cuantia=?, defecto=?, fecha_fin=? where id = ?;";

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
            $consulta ->bindparam(1, $nombre);            
            $consulta ->bindparam(2, $fecha);
            $consulta ->bindparam(3, $cuantia);
            $consulta ->bindparam(4, $defecto);
            $consulta ->bindparam(5, $f_fin);
            $consulta ->bindparam(6, $id);

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

    // eliminamos una cuenta por su id. La eliminación será lógica y no física
    public static function deleteCuentaById($id) {
        $prepareSql = "UPDATE cuentas set fecha_fin=sysdate() where id = ?;";

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
            $consulta ->bindparam(1, $id);

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

    // montamos una consulta dinámica
    public static function getCuentasByParams($user, $nombre,$cuantiaDesde,$cuantiaHasta,$fechaDesde,$fechaHasta){
        $consulta ="SELECT *,DATE_FORMAT(fecha,'%d-%m-%Y') as fecha FROM cuentas where usuario='".$user."'";

        if(isset($nombre)){
            $consulta = $consulta." and nombre like :nombre";
        }
        if(isset($cuantiaDesde)){
            $consulta = $consulta." and cuantia >= :cuantiaDesde";
        }
        if(isset($cuantiaHasta)){
            $consulta = $consulta." and cuantia<= :cuantiaHasta";
        }
        if(isset($fechaDesde)){
            $consulta = $consulta." and fecha>= :fechaDesde";
        }
        if(isset($fechaHasta)){
            $consulta = $consulta." and fecha<= :fechaHasta";
        }

        $conexion = DB::getConnection();
        $cuentas = array();
        try {
            // Consulta preparada
            $sql = $conexion->prepare($consulta);

            if(isset($nombre)){
                $nombre = "%".$nombre."%";
                $sql->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            }
            if(isset($cuantiaDesde)){
                $sql->bindParam(':cuantiaDesde', $cuantiaDesde, PDO::PARAM_STR);
            }
            if(isset($cuantiaHasta)){
                $sql->bindParam(':cuantiaHasta', $cuantiaHasta, PDO::PARAM_STR);
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
                    $cuentas[] = new Cuentas($row['id'], $row['nombre'], $row['usuario'], $row['fecha'], $row['cuantia'], $row['defecto'], $row['fecha_fin']);
                }
            }


        } catch (PDOException $e) {
            echo "ERROR - No se pudo acceder a la tabla paises: " . $e->getMessage();
        }

        return $cuentas;
    }
}
?>
