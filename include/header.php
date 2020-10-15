<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

error_reporting("E_ALL$~E_NOTICE&~E_WARNING");

spl_autoload_register(function ($clase) {
    include '../clases/' . $clase . '.php';
});

?>
