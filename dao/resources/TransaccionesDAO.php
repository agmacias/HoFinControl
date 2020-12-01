<?php
require_once '../DB.php';
require_once '../../model/Transacciones.php';

/**
 * Description of TransaccionesDAO
 *
 * @author Alejandro González
 */
class TransaccionesDAO {

    /*
     * Obtiene las transacciones según su usuario
    */
    public static function getTransaccionesByUser($user,$tipoIngreso) {
        $conexion = DB::getConnection();
        $cuentas = array();
        try {
            // Consulta preparada
            $sql = $conexion->prepare("SELECT transacciones.*,tipo_ingreso_gasto.nombre as nombre_ingreso_gasto,cuentas.nombre as nombreCuenta, DATE_FORMAT(transacciones.f_transaccion,'%d-%m-%Y') as f_transaccion from transacciones inner join tipo_ingreso_gasto on transacciones.ingreso_gasto = tipo_ingreso_gasto.id inner join cuentas on transacciones.cuenta = cuentas.id  where transacciones.usuario = :user and tipo_ingreso_gasto.isIngreso= :tipoIngreso");
            $sql->bindParam(':user', $user, PDO::PARAM_INT);
            $sql->bindParam(':tipoIngreso', $tipoIngreso, PDO::PARAM_INT);

            if($sql->execute()) {
                while ($row = $sql->fetch()) {
                    /*
                     * Construimos el objeto usuario a través del método explicito ya que queremos un objeto cargado con los datos
                     * que acabamos de leer de la base de datos.
                    */
                    $cuentas[] = new Transacciones($row['id'], $row['ingreso_gasto'], $row['nombre_ingreso_gasto'], $row['f_transaccion'], $row['asunto'], $row['descripcion'], $row['usuario'], $row['nombreCuenta'], $row['cuenta'], $row['cuantia']);
                }
            }


        } catch (PDOException $e) {
            echo "ERROR - No se pudo acceder a la tabla paises: " . $e->getMessage();
        }

        return $cuentas;
    }

    // obtiene las transacciones enf unción de los parámetros recibidos
    public static function getTransaccionesByParams($user, $cuenta, $tipo, $fechaDesde, $fechaHasta, $asunto, $tipoIngreso, $groupBy, $limit) {
        $conexion = DB::getConnection();
        $cuentas = array();
        try {
            $consulta = "SELECT transacciones.*,tipo_ingreso_gasto.nombre as nombre_ingreso_gasto,cuentas.nombre as nombreCuenta, DATE_FORMAT(transacciones.f_transaccion,'%d-%m-%Y') as f_transaccion from transacciones inner join tipo_ingreso_gasto on transacciones.ingreso_gasto = tipo_ingreso_gasto.id inner join cuentas on transacciones.cuenta = cuentas.id  where transacciones.usuario = :user";

            // montar la consulta en función de los parámetros recibidos
            if(isset($cuenta)){
               $consulta = $consulta." and transacciones.cuenta in(".$cuenta.") ";
            }
            if(isset($tipo)){
               $consulta = $consulta." and transacciones.ingreso_gasto in(".$tipo.")";
            }
            if(isset($fechaDesde)){
               $consulta = $consulta." and transacciones.f_transaccion >= :fechaDesde ";
            }
            if(isset($fechaHasta)){
               $consulta = $consulta." and transacciones.f_transaccion <= :fechaHasta ";
            }
            if(isset($asunto)){
               $consulta = $consulta." and transacciones.asunto like  :asunto  ";
            }
            if(isset($tipoIngreso)){
                $consulta = $consulta." and tipo_ingreso_gasto.isIngreso = :tipoIngreso";
            }
            if(isset($limit)){
                $consulta = $consulta." limit :limite  ";
            }
            if(isset($groupBy)){
                /* no podemos agrupar, sino devolveríamos menos registros debido a que estarían agrupados
                 * devolveremos los registros ordenados y le daremos el control al front para que pueda
                 * agrupar de una manera más óptima estos registros
                 */
                if($groupBy=="cuenta"){
                    $consulta = $consulta." order by transacciones.cuenta";
                }else{
                    $consulta = $consulta." order by ingreso_gasto";
                }

            }
            // Consulta preparada
            $sql = $conexion->prepare($consulta);

            // establecemos los valores
            $sql->bindParam(':user', $user, PDO::PARAM_INT);

            /*if(isset($cuenta)){
               $sql->bindParam(':cuenta', $cuenta, PDO::PARAM_INT);
            }
            if(isset($tipo)){
               $sql->bindParam(':tipo', $tipo, PDO::PARAM_INT);
            }*/
            if(isset($fechaDesde)){
               $sql->bindParam(':fechaDesde', $fechaDesde, PDO::PARAM_STR);
            }
            if(isset($fechaHasta)){
               $sql->bindParam(':fechaHasta', $fechaHasta, PDO::PARAM_STR);
            }
            if(isset($asunto)){
               $asunto= "%".$asunto."%";
               $sql->bindParam(':asunto', $asunto, PDO::PARAM_STR);
            }
            if(isset($tipoIngreso)){
                $sql->bindParam(':tipoIngreso', $tipoIngreso, PDO::PARAM_INT);
            }
            if(isset($limit)){
                $sql->bindParam(':limite', $limit, PDO::PARAM_INT);
            }

            if($sql->execute()) {
                while ($row = $sql->fetch()) {

                    $cuentas[] = new Transacciones($row['id'], $row['ingreso_gasto'], $row['nombre_ingreso_gasto'], $row['f_transaccion'], $row['asunto'], $row['descripcion'], $row['usuario'], $row['nombreCuenta'], $row['cuenta'], $row['cuantia']);
                }
            }


        } catch (PDOException $e) {
            echo "ERROR - No se pudo acceder a la tabla paises: " . $e->getMessage();
        }

        return $cuentas;
    }

    // Insertará una transacción en bbdd
    public static function insertTransaccion($cuenta,$tipo,$usuario,$fecha,$asunto,$descripcion, $cuantia) {
        $prepareSql = "INSERT INTO transacciones (asunto, descripcion, f_transaccion, usuario, ingreso_gasto, cuenta, cuantia) values (?,?,?,?,?,?,?);";

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
            $consulta ->bindparam(1, $asunto);
            $consulta ->bindparam(2, $descripcion);
            $consulta ->bindparam(3, $fecha);
            $consulta ->bindparam(4, $usuario);
            $consulta ->bindparam(5, $tipo);
            $consulta ->bindparam(6, $cuenta);
            $consulta ->bindparam(7, $cuantia);

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

    // Actualizará una transacción en bbdd
    public static function updateTransaccion($id, $cuenta,$tipo,$usuario,$fecha,$asunto,$descripcion, $cuantia) {
        $prepareSql = "UPDATE transacciones SET asunto=? , descripcion=?, f_transaccion=?, usuario=?, ingreso_gasto=? , cuenta=?, cuantia=? where id=?";

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
            $consulta ->bindparam(1, $asunto);
            $consulta ->bindparam(2, $descripcion);
            $consulta ->bindparam(3, $fecha);
            $consulta ->bindparam(4, $usuario);
            $consulta ->bindparam(5, $tipo);
            $consulta ->bindparam(6, $cuenta);
            $consulta ->bindparam(7, $cuantia);
            $consulta ->bindparam(8, $id);

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

    // Elimina una transacción en bbdd
    public static function deleteTransaccion($id) {
        $prepareSql = "DELETE from transacciones where id=?";

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

    public static function getTotalesTransaccionesByUserAndCuenta($user,$cuenta){
        $consulta = "select * from (".
               "SELECT sum(cuantia) as total_gasto from transacciones as tran inner join tipo_ingreso_gasto as tip on tran.ingreso_gasto=tip.id and tip.isIngreso=0 and tran.usuario=:user and tran.cuenta=:cuenta) as t1,".
               "(SELECT sum(cuantia) as total_ingreso from transacciones as tran inner join tipo_ingreso_gasto as tip on tran.ingreso_gasto=tip.id and tip.isIngreso=1 and tran.usuario=:user and tran.cuenta=:cuenta) as t2";

        $conexion = DB::getConnection();
        $transacciones = array();
        try {
            // Consulta preparada
            $sql = $conexion->prepare($consulta);
            $sql->bindParam(':user', $user, PDO::PARAM_INT);
            $sql->bindParam(':cuenta', $cuenta, PDO::PARAM_INT);
            
            if($sql->execute()) {
                while ($row = $sql->fetch()) {
                    /*
                     * Construimos el objeto usuario a través del método explicito ya que queremos un objeto cargado con los datos
                     * que acabamos de leer de la base de datos.
                    */
                    $transacciones['total_gasto'] = $row['total_gasto'];
                    $transacciones['total_ingreso'] = $row['total_ingreso'];
                }
            }
        } catch (PDOException $e) {
            echo "ERROR - No se pudo acceder a la tabla paises: " . $e->getMessage();
        }

        return $transacciones;

    }

    public static function getTransaccionesByYearUserCeuntaAndTipo($anio,$user,$cuenta){
        $consulta = "SELECT tran.id, MONTH(f_transaccion) as mes ,sum(cuantia) as cuantia ,asunto,isIngreso FROM transacciones as tran inner join tipo_ingreso_gasto as tip on tran.ingreso_gasto = tip.id WHERE YEAR(f_transaccion)=:anio and usuario=:usuario and cuenta=:cuenta group by MONTH(f_transaccion),isIngreso order by f_transaccion asc;";

        $conexion = DB::getConnection();
        $transacciones = array();
        try {
            // Consulta preparada
            $sql = $conexion->prepare($consulta);
            $sql->bindParam(':anio', $anio, PDO::PARAM_INT);
            $sql->bindParam(':usuario', $user, PDO::PARAM_INT);            
            $sql->bindParam(':cuenta', $cuenta, PDO::PARAM_INT);

            if($sql->execute()) {
                while ($row = $sql->fetch()) {
                    
                    $transacciones[] = array("mes"=>$row['mes'],"cuantia"=>$row['cuantia'],"asunto"=>$row['asunto'],"isIngreso"=>$row['isIngreso']);

                   // new Transacciones($row['id'], $row['ingreso_gasto'], $row['nombre_ingreso_gasto'], $row['f_transaccion'], $row['asunto'], $row['descripcion'], $row['usuario'], $row['nombreCuenta'], $row['cuenta'], $row['cuantia']);
                }
            }
        } catch (PDOException $e) {
            echo "ERROR - No se pudo acceder a la tabla paises: " . $e->getMessage();
        }

        return $transacciones;
    }

    public static function getGastosByUserYearAndCount($user,$cuenta,$anio){
        $consulta = "select sum(cuantia) as cuantia, nombre from transacciones tran inner join tipo_ingreso_gasto tip on tran.ingreso_gasto = tip.id where YEAR(f_transaccion)=:anio and usuario=:usuario and cuenta=:cuenta and isIngreso=0 group by(ingreso_gasto);";

        $conexion = DB::getConnection();
        $transacciones = array();
        try {
            // Consulta preparada
            $sql = $conexion->prepare($consulta);
            $sql->bindParam(':anio', $anio, PDO::PARAM_INT);
            $sql->bindParam(':usuario', $user, PDO::PARAM_INT);
            $sql->bindParam(':cuenta', $cuenta, PDO::PARAM_INT);

            if($sql->execute()) {
                while ($row = $sql->fetch()) {

                    $transacciones[] = array("cuantia"=>$row['cuantia'],"nombre"=>$row['nombre']);
                }
            }
        } catch (PDOException $e) {
            echo "ERROR - No se pudo acceder a la tabla paises: " . $e->getMessage();
        }

        return $transacciones;
    }
}
?>
