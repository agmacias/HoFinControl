<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

error_reporting("E_ALL$~E_NOTICE&~E_WARNING");

spl_autoload_register(function ($clase) {
            include '../model/' . $clase . '.php';
        })


;
session_start();

$usuario = Usuario::implicito();
$usuario->unserialize($_SESSION['usuario']);

if (!isset($_SESSION['usuario'])) {
    die("Error - debe <a href='login.php'>identificarse</a>.<br />");
}
?>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="HoFiControl" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Home Financial Control</title>

        <?php
        include_once '../include/scripts.html';
        ?>
        <script src="../controller/App.js"></script>
        <script src="../controller/DashboardController.js"></script>
        <script src="../controller/CabeceraController.js"></script>
        <script src="../controller/ProfileController.js"></script>
        <script src="../controller/ControladorController.js"></script>
        <script src="../controller/CuentasController.js"></script>
        <script src="../controller/MantIngresosController.js"></script>
        <script src="../controller/MantGastosController.js"></script>
        <script src="../js/Chart.js"></script>

        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.min.js"></script>
        <script type="text/javascript" src="https://cdn.rawgit.com/jtblin/angular-chart.js/master/dist/angular-chart.min.js"></script>
        

    </head>
    <body ng-app="app">
        <div class="jaula" ng-controller="ControladorController" ng-init="init()">
            <div class="menu">
                <div class="logo">
                    <a href="#">Logotipo</a>
                </div>
                <div class="nav flex-column nav-pills marginMenu" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true" ng-click="selectOptionMenu(1);"><span><i class="fa fa-desktop" aria-hidden="true"></i></span>Dashboard</a>
                    <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false" ng-click="selectOptionMenu(2);"><span><i class="fa fa-cog" aria-hidden="true"></i></span>Perfil</a>
                    <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false" ng-click="selectOptionMenu(3)"><span><i class="fa fa-braille" aria-hidden="true"></i></span>Cuentas</a>
                    <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false" ng-click="selectOptionMenu(4)"><span><i class="fa fa-database" aria-hidden="true"></i></span>Mantenimiento tablas maestras</a>
                    <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false" ng-click="selectOptionMenu(5)"><span><i class="fa fa-area-chart" aria-hidden="true"></i></span>Mantenimiento de gastos</a>
                    <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false" ng-click="selectOptionMenu(6)"><span><i class="fa fa-signal" aria-hidden="true"></i></span>Mantenimiento de ingresos</a>
                    <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false" ng-click="selectOptionMenu(7)"><span><i class="fa fa-list" aria-hidden="true"></i></span>Listados</a>
                </div>
            </div>
            <!-- INICIO PANEL PRINCIPAL-->
            <div class="panelPrincipal">
                <!-- Directiva para la cabecera -->
                <div data-ng-if="cabeceraData!=undefined">
                    <cabecera data-in="cabeceraData"></cabecera>
                </div>
                <!-- Fin directiva para la cabecera -->
                
                <!-- Directiva del dashBoard -->
                <div data-ng-if="opcion==1 && dashboardData!=undefined">
                    <dash-board data="dashboardData"></dash-board>
                </div>
                <!-- Fin Directiva del dashBoard -->
                <!-- Directiva del profile -->
                <div data-ng-if="opcion==2">
                    <profile data="profileData"></profile>
                </div>
                <!-- Fin Directiva del profile -->
                <!-- Directiva del profile -->
                <div data-ng-if="opcion==3">
                    <cuentas data="cuentasData"></cuentas>
                </div>
                <!-- Fin Directiva del profile -->

                <!-- Directiva de gastos -->
                <div data-ng-if="opcion==5">
                    <gastos data="ingresosData"></gastos>
                </div>
                <!-- Fin Directiva de gastos -->

                <!-- Directiva de ingresos -->
                <div data-ng-if="opcion==6">
                    <ingresos data="ingresosData"></ingresos>
                </div>
                <!-- Fin Directiva de ingresos -->
            </div>
        </div>
    </body>