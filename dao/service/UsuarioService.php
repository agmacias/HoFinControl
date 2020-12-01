<?php

require_once('../resources/UsuarioDAO.php');
require_once('../resources/PermisosDAO.php');

/*
	Leemos el flujo que hay en php://input
	Como codificamos los datos con JSON al enviarlos,
	al recibirlos tenemos que decodificarlos de la misma manera.
	Para ello, PHP provee el método json_decode: http://php.net/manual/es/function.json-decode.php
*/


$datos = json_decode(file_get_contents("php://input"));
$response = array();
$opcion = $datos->opcion;

//hemos hecho login, obtenemos usuarioExplicito
if($opcion==1){
    // antes de nada eliminamos la sesion activa
    session_destroy();

    $usuario = $datos->usuario;
    $password = $datos->password;

    $usuarioLogado = Usuario::implicito();
    $usuarioLogado = UsuarioDAO::getUsuarioExplicito($usuario, $password);

    if ($usuarioLogado != null) {
        // Si las credenciales son válidas metemos el usuario en sesion y entramos a la aplicación
        session_start();
        $_SESSION['usuario'] = $usuarioLogado->serialize($usuario);

        $response = array('response' => true, 'mensaje' => 'controlador.php');
    } else {
            // Si las credenciales no son válidas, se vuelven a pedir y se muestra un mensaje de error
            $response = array('response' => false, 'mensaje' => 'Usuario o contraseña no válidos');
    }
}else if($opcion==2){
    // alta de usuario
    $usuario = $datos->usuario;    
    $nombre = $datos->nombre;
    $apellido1 = $datos->apellido1;
    $apellido2 = $datos->apellido2;
    $password = $datos->contrasenia;
    
    if(isset($datos->nacimiento)) {
        $timestamp = strtotime($datos->nacimiento);
        $f_nacimiento = date('Y-m-d H:i:s', $timestamp);
    }else {
        $f_nacimiento = null;
    }
    $pais=null;
    if(isset($datos->pais)){
        $pais = $datos->pais;
    }
    $telefono = null;
    if(isset($datos->telefono)){
        $telefono = $datos->telefono;
    }
    $direccion = null;
    if(isset($datos->direccion)){
        $direccion = $datos->direccion;
    }
    $email = null;
    if(isset($datos->email)){
        $email = $datos->email;
    }

    $usuarioAlta = UsuarioDAO::getUsuarioExplicito($usuario, null);

    if($usuarioAlta==null){
        $result = UsuarioDAO::insertUsuario($usuario, $password, $nombre, $apellido1, $apellido2, $f_nacimiento, $pais, $telefono, $direccion, $email);

        // el siguiente paso será dar de alta una serie de permisos por defecto al usuario.
        // [Dashboard, Perfil, Mantenimiento de Gastos y Mantenimiento de ingresos]

        if($result==true){
            $usuarioAlta = UsuarioDAO::getUsuario($usuario);

            $result = PermisosDAO::insertPermisos($usuarioAlta->getId(), 1);
            $result = PermisosDAO::insertPermisos($usuarioAlta->getId(), 2);
            $result = PermisosDAO::insertPermisos($usuarioAlta->getId(), 3);
            $result = PermisosDAO::insertPermisos($usuarioAlta->getId(), 5);
            $result = PermisosDAO::insertPermisos($usuarioAlta->getId(), 6);

            if($result==true){
                $response = array('response' => true, 'mensaje' => 'Usuario dado de alta correctamente.');
            }else{
                $response = array('response' => false, 'mensaje' => 'Se ha producido un error en el guardado de datos.');
            }
        }
    }else{
        $response = array('response' => false, 'mensaje' => 'El usuario que intenta dar de alta ya existe en base de datos.');
    }
    
}else if($opcion==3){
    // modificación de usuario
    $id = $datos->id;
    $usuario = $datos->usuario;    
    $nombre = $datos->nombre;
    $apellido1 = $datos->apellido1;
    $apellido2 = $datos->apellido2;
   
    $password = null;
    if(isset($datos->contrasenia)){
        $password = $datos->contrasenia;
    }
    if(isset($datos->nacimiento)) {
        $timestamp = strtotime($datos->nacimiento);
        $f_nacimiento = date('Y-m-d H:i:s', $timestamp);
    }else {
        $f_nacimiento = null;
    }
    $pais=null;
    if(isset($datos->pais)){
        $pais = $datos->pais;
    }
    $telefono = null;
    if(isset($datos->telefono)){
        $telefono = $datos->telefono;
    }
    $direccion = null;
    if(isset($datos->direccion)){
        $direccion = $datos->direccion;
    }
    $email = null;
    if(isset($datos->email)){
        $email = $datos->email;
    }

    $result = UsuarioDAO::updateUsuario($id, $usuario, $password, $nombre, $apellido1, $apellido2, $f_nacimiento, $pais, $telefono, $direccion, $email);

    if($result==true){
        $response = array('response' => true, 'mensaje' => 'Usuario modificado correctamente.');
    }else{
        $response = array('response' => false, 'mensaje' => 'Se ha producido un error en el guardado de datos.');
    }
}else if($opcion==4){
    
    $usuario = Usuario::implicito();
    $usuario->unserialize($_SESSION['usuario']);
    // obtendremos los datos del usuario para el mantenimiento de perfil
    $usuarioLogado = UsuarioDAO::getUsuario($usuario->getUsuario());

    // mapeamos los datos para que sean convertidos a Json       
    $response['id']=$usuarioLogado->getId();
    $response['usuario']=$usuarioLogado->getUsuario();
    $response['password']=$usuarioLogado->getPassword();
    $response['nombre']=$usuarioLogado->getNombre();
    $response['apellido1']=$usuarioLogado->getApellido1();
    $response['apellido2']=$usuarioLogado->getApellido2();
    $response['f_nacimiento']=$usuarioLogado->getF_nacimiento();
    $response['pais']=$usuarioLogado->getPais();
    $response['telefono']=$usuarioLogado->getTelefono();
    $response['direccion']=$usuarioLogado->getDireccion();
    $response['mail']=$usuarioLogado->getMail();
}else if($opcion==5){
    // listado de usuarios dados de alta
    $usuario = null;
    $nombre = null;
    $apellido = null;
    $apellido2 = null;
    $fechaDesde = null;
    $fechaHasta = null;
    if(isset($datos->usuario)){
        $usuario = $datos->usuario;
    }
    if(isset($datos->nombre)){
        $nombre = $datos->nombre;
    }
    if(isset($datos->apellido)){
        $apellido = $datos->apellido;
    }
    if(isset($datos->apellido2)){
        $apellido2 = $datos->apellido2;
    }
    if(isset($datos->fechaDesde)){
        $fechaDesde = $datos->fechaDesde;
    }
    if(isset($datos->fechaHasta)){
        $fechaHasta = $datos->fechaHasta;
    }

    $usuarios = UsuarioDAO::getUsuariosByParams($usuario, $nombre, $apellido, $apellido2, $fechaDesde, $fechaHasta);

    foreach ($usuarios as $k => $row) {
        $response[]=array("id"=>$row->getId(),"usuario"=>$row->getUsuario(),
                "nombre"=>$row->getNombre(),
                "apellido1"=>$row->getApellido1(),"apellido2"=>$row->getApellido2(),
                "f_nacimiento"=>$row->getF_nacimiento());
    }

}

//no hacer return, sino echo
header('Content-Type: application/json');
echo json_encode($response);

?>


