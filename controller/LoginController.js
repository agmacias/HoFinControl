angular.module('app', [])
    .controller('LoginController', ['$scope','$http', function($scope,$http) {
        $scope.init = function(){
            if (localStorage.chkbx && localStorage.chkbx != '') {
                $scope.rememberMe = localStorage.chkbx;
                $scope.usuario = localStorage.usrname;
                $scope.password = localStorage.pass
            }
        };

        $scope.sendLogin = function() {
            var loginIn = {
                opcion: 1,
                usuario: $scope.usuario,
                password: $scope.password
            };

            remember();

            $http.post("../dao/service/UsuarioService.php", angular.toJson(loginIn))
            .then(function(respuesta){
                /*
                    Esto se ejecuta si todo va bien. Podemos leer la respuesta
                    que nos dio el servidor accediendo a la propiedad data
                    Recordemos que tenemos que decodificarla, ya que si enviamos con JSON
                    deben respondernos con JSON (no es obligatorio, pero sí es una buena práctica)
            */
                console.log("Petición terminada. La respuesta es: ", angular.fromJson(respuesta.data));

                if(respuesta.data.response==true){
                    window.location.href=respuesta.data.mensaje;
                }else{
                    $scope.login={
                        error:respuesta.data.mensaje
                    };
                }
            });
        };

        /* función que nos ayuda a guardar en localStorage los datos del login en caso de haber
         * pulsado en el botón de recordar
        */
        function remember(){
            if ($scope.rememberMe) {
                // save username and password
                localStorage.usrname = $scope.usuario;
                localStorage.pass = $scope.password;
                localStorage.chkbx = $scope.rememberMe;
            }
        }
    }]);

