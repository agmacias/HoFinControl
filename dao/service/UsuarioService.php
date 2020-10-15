<?php

require_once('../resources/UsuarioDAO.php');

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
    $password = $datos->contrasenia;
    $nombre = $datos->nombre;
    $apellido1 = $datos->apellido1;
    $apellido2 = $datos->apellido2;
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

        if($result==true){
            $response = array('response' => true, 'mensaje' => 'Usuario dado de alta correctamente.');
        }else{
            $response = array('response' => false, 'mensaje' => 'Se ha producido un error en el guardado de datos.');
        }
    }else{
        $response = array('response' => false, 'mensaje' => 'El usuario que intenta dar de alta ya existe en base de datos.');
    }
    
}else if($opcion==3){
    // modificación de usuario
    $id = $datos->id;
    $usuario = $datos->usuario;
    $password = $datos->contrasenia;
    $nombre = $datos->nombre;
    $apellido1 = $datos->apellido1;
    $apellido2 = $datos->apellido2;
    $f_nacimiento = $datos->nacimiento;
    $pais = $datos->pais;
    $telefono = $datos->telefono;
    $direccion = $datos->direccion;
    $email = $datos->email;

    $result = UsuarioDAO::updateUsuario($id, $usuario, $password, $nombre, $apellido1, $apellido2, $f_nacimiento, $pais, $telefono, $direccion, $email);

    if($result==true){
        $response = array('response' => true, 'mensaje' => 'Usuario modificado correctamente.');
    }else{
        $response = array('response' => false, 'mensaje' => 'Se ha producido un error en el guardado de datos.');
    }
}else{
    
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
}

//no hacer return, sino echo
header('Content-Type: application/json');
echo json_encode($response);

?>


