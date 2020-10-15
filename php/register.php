<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Home Financial Control</title>

        <?php
        include_once '../include/scripts.html';
        ?>        
        <script src="../controller/RegisterController.js"></script>
    </head>
    <body ng-app="app">
        <div ng-controller="RegisterController" ng-init="init()">
            <div class="content">
                <div class="container-fluid login-form">
                    <h1 class="well">Formulario de registro</h1>
                    <form>
                        <div>
                            <!-- inicio tarjetas -->
                            <div class="card card-margin">
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label class ="cabeceraLabel" for="nombre"><u>N</u>ombre (obligatorio):</label>
                                        <input type="text" class="form-control" id="nombre" ng-model="profileView.nombre" placeholder="Nombre" accesskey="N" tabindex="1" maxlength="45" required />
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class ="cabeceraLabel" for="apellido1"><u>P</u>rimer apellido (obligatorio):</label>
                                        <input type="text" class="form-control" id="apellido1" ng-model="profileView.apellido1" placeholder="Primer apellido" accesskey="P" tabindex="2" maxlength="45" required />
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class ="cabeceraLabel" for="apellido2"><u>S</u>egundo apellido (obligatorio):</label>
                                        <input type="text" class="form-control" id="apellido2" ng-model="profileView.apellido2" placeholder="Segundo apellido" accesskey="S" tabindex="3" maxlength="45" required />
                                    </div>
                                </div>
                                <div class="form-row">

                                    <div class="form-group col-md-4">
                                        <label class ="cabeceraLabel" for="nacimiento"><u>F</u>echa nacimiento</label>
                                        <input type="date" class="form-control" id="nacimiento" ng-model="profileView.nacimiento" placeholder="Fecha nacimiento" accesskey="F" tabindex="4" maxlength="12" />
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class ="cabeceraLabel" for="pais">P<u>a</u>ís:</label>
                                        <select name="pais" id="pais" ng-model="profileView.pais" class="form-control" accesskey="A" tabindex="5">
                                            <option value="0" selected="selected">Seleccione un pais</option>
                                            <option ng-repeat="option in data.paises" value="{{option.id}}">{{option.nombre}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class ="cabeceraLabel" for="telefono"><u>T</u>eléfono:</label>
                                        <input type="text" class="form-control" id="telefono" placeholder="Teléfono" ng-model="profileView.telefono" accesskey="T" tabindex="6" maxlength="11" />
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-8">
                                        <label class ="cabeceraLabel" for="direccion"><u>D</u>irección:</label>
                                        <input type="text" class="form-control" id="direccion" placeholder="Dirección" ng-model="profileView.direccion" accesskey="D" tabindex="7" maxlength="50" />
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-8">
                                        <label class ="cabeceraLabel" for="mail"><u>E</u>-Mail:</label>
                                        <input type="text" class="form-control" id="mail" placeholder="E-Mail" ng-model="profileView.mail" accesskey="D" tabindex="8" maxlength="50" />
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label class ="cabeceraLabel" for="usuario"><u>U</u>suario:</label>
                                        <input type="text" class="form-control" id="usuario" ng-model="profileView.usuario" placeholder="Usuario" accesskey="U" tabindex="9" maxlength="20" required/>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class ="cabeceraLabel" for="contrasena"><u>C</u>ontraseña:</label>
                                        <input type="password" class="form-control" id="contrasena" ng-model="profileView.contrasena" placeholder="Contraseña" accesskey="C" tabindex="9" maxlength="10" required/>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class ="cabeceraLabel" for="contrasena2"><u>C</u>onfirmar contraseña:</label>
                                        <input type="password" class="form-control" id="contrasena2" ng-model="profileView.contrasena2" placeholder="Confirmar contraseña" accesskey="O" tabindex="10" maxlength="10" required />
                                    </div>
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-sm" id="acepar" name="aceptar" tabindex="11" ng-click="sendRegister()">Aceptar</button>
                                    <button type="reset" class="btn btn-primary btn-sm" id="cancelar" name="cancelar" tabindex="12">Limpiar</button>
                                    <button class="btn btn-primary btn-sm" id="cancelar" name="cancelar" ng-click="volver()" tabindex="13">Volver</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>