<?php
require_once '../DB.php';
require_once '../../model/TipoIngresoGasto.php';
/**
 * Description of IngresoGastoDAO
 *
 * @author Administrador
 */
class IngresoGastoDAO {

    public static function getTipoIngresoGasto($isIngreso) {
        $conexion = DB::getConnection();
        $paises = array();
        try {
            // Consulta preparada
            $sql = $conexion->prepare("SELECT * FROM tipo_ingreso_gasto where isIngreso = :isIngreso");
            $sql->bindParam(':isIngreso', $isIngreso, PDO::PARAM_STR);

            if($sql->execute()) {
                while ($row = $sql->fetch()) {
                    /*
                     * Construimos el objeto usuario a través del método explicito ya que queremos un objeto cargado con los datos
                     * que acabamos de leer de la base de datos.
                    */
                    $paises[] = new TipoIngresoGasto($row['id'], $row['nombre'],$row['isIngreso']);
                }
            }

        } catch (PDOException $e) {
            echo "ERROR - No se pudo acceder a la tabla tipo_ingreso_gasto: " . $e->getMessage();
        }

        return $paises;
    }

}
?>
