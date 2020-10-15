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
     * Obtiene un listado de todas los registros de la tabla cuentas
    */
    public static function getCuentasByUser($user) {
        $conexion = DB::getConnection();
        $cuentas = array();
        try {
            // Consulta preparada
            $sql = $conexion->prepare("SELECT cuentas.*, tipo_cuenta.nombre as tipoCuenta FROM cuentas inner join tipo_cuenta on cuentas.tipo=tipo_cuenta.id where (cuentas.fecha_fin is null or cuentas.fecha_fin>sysdate()) and usuario='".$user."';");

            if($sql->execute()) {
                while ($row = $sql->fetch()) {
                    /*
                     * Construimos el objeto usuario a través del método explicito ya que queremos un objeto cargado con los datos
                     * que acabamos de leer de la base de datos.
                    */
                    $cuentas[] = new Cuentas($row['id'], $row['nombre'], $row['tipoCuenta'], $row['usuario'], $row['fecha'], $row['cuantia'], $row['defecto'], $row['fecha_fin'], $row['tipo']);
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
                    $cuentas[] = new Cuentas($row['id'], $row['nombre'], $row['tipo'], $row['usuario'], $row['fecha'], $row['cuantia'], $row['defecto']);
                }
            }


        } catch (PDOException $e) {
            echo "ERROR - No se pudo acceder a la tabla paises: " . $e->getMessage();
        }

        return $cuentas;
    }

    // Inserta una cuenta según los parámetros pasados
    public static function insertCuenta($nombre,$tipo,$usuario,$fecha,$cuantia,$defecto,$f_fin) {
        $prepareSql = "INSERT into cuentas (nombre,tipo,usuario,fecha,cuantia,defecto,fecha_fin) VALUES (?,?,?,?,?,?,?);";

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
            $consulta ->bindparam(2, $tipo);
            $consulta ->bindparam(3, $usuario);
            $consulta ->bindparam(4, $fecha);
            $consulta ->bindparam(5, $cuantia);
            $consulta ->bindparam(6, $defecto);
            $consulta ->bindparam(7, $f_fin);

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

    public static function updateCuentaById($id,$nombre,$tipo,$fecha,$cuantia,$defecto,$f_fin) {
        $prepareSql = "UPDATE cuentas set nombre=?, tipo=?, fecha=?, cuantia=?, defecto=?, fecha_fin=? where id = ?;";

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
            $consulta ->bindparam(2, $tipo);
            $consulta ->bindparam(3, $fecha);
            $consulta ->bindparam(4, $cuantia);
            $consulta ->bindparam(5, $defecto);
            $consulta ->bindparam(6, $f_fin);
            $consulta ->bindparam(7, $id);

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
}
?>
