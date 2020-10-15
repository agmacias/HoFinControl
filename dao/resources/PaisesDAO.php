<?php

require_once '../DB.php';
require_once '../../model/Pais.php';
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of PaisDAO
 *
 * @author Administrador
 */
class PaisesDAO {
    //put your code here

    public static function getPaises() {
        $conexion = DB::getConnection();
        $paises = array();
        try {
            // Consulta preparada
            $sql = $conexion->prepare("SELECT * FROM paises");            

            if($sql->execute()) {
                while ($row = $sql->fetch()) {
                    /*
                     * Construimos el objeto usuario a través del método explicito ya que queremos un objeto cargado con los datos
                     * que acabamos de leer de la base de datos.
                    */
                    $paises[] = new Pais($row['id'], $row['nombre']);
                }
            }


        } catch (PDOException $e) {
            echo "ERROR - No se pudo acceder a la tabla paises: " . $e->getMessage();
        }

        return $paises;
    }
}
?>
