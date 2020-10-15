<?php
    // Recuperamos la información de la sesión
    session_start();

    // Y la eliminamos
    unset($_SESSION['usuario']);
    echo json_encode("login.php");