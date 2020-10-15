<?php

require_once 'DB.php';
require_once 'Configuracion.php';


// Abrimos la sesión
session_start();

class DB {

    // Método que obtiene la conexion a la base de datos con el valor del objeto cofiguración pasado por sesión
    public static function getConnection() {
        $conexion = null;

        // Cumplimentamos los parámetros necesarios para crear la cadena de conexión a la base de datos
        $opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
        $dns = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
        $usuario = DB_USER;
        $contrasena = DB_PASSWORD;
        // Realizamos la conexión
        try {
            $conexion = new PDO($dns, $usuario, $contrasena, $opc);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'ERROR - No se ha podido conectar con la BD: ' . $e->getMessage();
        }
        return $conexion;
    }

    // Método que obtiene el listado de todos los productos. Devuelve un array de objetos producto
    /*public static function getProductos() {
        $conexion = self::getConnection();

        try {

            $sql = "SELECT cod, nombre_corto, nombre, PVP FROM producto;";
            $resultado = $conexion->query($sql);
            $productos = array();

            if ($resultado) {
                $row = $resultado->fetch();
                while ($row != null) {

                    $productos[] = new Producto($row);
                    $row = $resultado->fetch();
                }
            }

            $conexion = null;

            return $productos;
        } catch (PDOException $e) {
            echo "ERROR - No se pudieron obtener los productos: " . $e->getMessage();
        }
    }

    // Método que obtiene la información de un producto determinado. Devuelve un objeto producto
    public static function getProducto($codigo) {
        $conexion = self::getConnection();
        try {
            // Se utiliza una consulta preparada
            $sql = $conexion->prepare("SELECT cod, nombre_corto, nombre, PVP FROM producto WHERE cod = ?");

            if ($sql->execute(array($codigo))) {
                while ($row = $sql->fetch()) {
                    $producto = new Producto($row);
                }
            }
            return $producto;
        } catch (PDOException $e) {
            echo "ERROR - No se pudo obtener el producto (" . $codigo . "): " . $e->getMessage();
        }
    }*/

    // Método que averigua si el login es correcto. Devuelve el objeto usuario, si es correcto o null en caso contrario
    /*public static function getUsuario($nombre, $contrasena) {
        $conexion = self::getConnection();
        $usuario = null;
        try {
            // Consulta preparada
            $sql = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = :usuario AND contrasena = md5(:contrasena)");
            $sql->bindParam(':usuario', $nombre, PDO::PARAM_STR);
            $sql->bindParam(':contrasena', $contrasena, PDO::PARAM_STR);

            if($sql->execute()) {
                while ($row = $sql->fetch()) {                    
                    $usuario = Usuario::explicito($row['usuario'], $row['contrasena'], $row['nombre'], $row['apellidos']);
                }
            }


        } catch (PDOException $e) {
            echo "ERROR - No se pudo acceder a la tabla usuarios: " . $e->getMessage();
        }

        return $usuario;
    }*/

}
