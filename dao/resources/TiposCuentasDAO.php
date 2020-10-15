<?php

require_once '../DB.php';
require_once '../../model/TiposCuentas.php';

/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TiposCuentasDAO
 *
 * @author Administrador
 */
class TiposCuentasDAO {

    public static function getTiposCuentas() {
        $conexion = DB::getConnection();
        $tipos = array();
        try {
            // Consulta preparada
            $sql = $conexion->prepare("SELECT * FROM tipo_cuenta");

            if($sql->execute()) {
                while ($row = $sql->fetch()) {
                    /*
                     * Construimos el objeto usuario a través del método explicito ya que queremos un objeto cargado con los datos
                     * que acabamos de leer de la base de datos.
                    */
                    $tipos[] = new TiposCuentas($row['id'], $row['nombre']);
                }
            }


        } catch (PDOException $e) {
            echo "ERROR - No se pudo acceder a la tabla paises: " . $e->getMessage();
        }

        return $tipos;
    }
}
?>
