angular.module('app', [])
    .controller('LoginController', ['$scope','$http', function($scope,$http) {        
        $scope.sendLogin = function() {
            var loginIn = {
                opcion: 1,
                usuario: $scope.usuario,
                password: $scope.password
            };

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
    }]);

