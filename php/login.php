<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Home Financial Control</title>

        <?php
            include_once '../include/scripts.html';
        ?>
        <script src="../controller/LoginController.js"></script>
    </head>
    <body ng-app="app">
        <div class="login-form login" ng-controller="LoginController">
            <form>
                <div class="avatar">
                    <img src="../img/avatar.png" alt="Avatar">
                </div>
                <h2 class="text-center">Inicio de sesión</h2>
                <h6 class="text-center error">{{login.error}}</h6>
                <div class="form-group">
                    <input type="text" class="form-control" name="usuario" ng-model="usuario" placeholder="Usuario" required="required">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" ng-model="password" placeholder="Password" required="required">
                </div>
                <div class="form-group">
                    <button type="submit" ng-click="sendLogin()" class="btn btn-primary btn-lg btn-block">Login</button>
                </div>
                <div class="bottom-action clearfix">
                    <label class="float-left form-check-label"><input type="checkbox">Recuérdame</label>
                    <a href="#" class="float-right">¿Olvidaste la contraseña?</a>
                </div>
            </form>
            <p class="text-center small">No tengo una cuenta <a href="register.php">Registrarse</a></p>
        </div>
    </body>
</html>