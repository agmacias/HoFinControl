var app = angular.module('app', ["ui.bootstrap"]);
app.controller('RegisterController', ['$scope','$http','$uibModal', function($scope,$http,$uibModal) {

        $scope.init = function(){
            $scope.profileView={};
            $scope.profileView.pais="0";
            // cargamos el combo de paises
            $http.post("../dao/service/PaisesService.php", angular.toJson({}))
            .then(function(respuesta){
                    /*
                        Esto se ejecuta si todo va bien. Podemos leer la respuesta
                        que nos dio el servidor accediendo a la propiedad data
                        Recordemos que tenemos que decodificarla, ya que si enviamos con JSON
                        deben respondernos con JSON (no es obligatorio, pero sí es una buena práctica)
                */
                    console.log("Petición terminada. La respuesta es: ", angular.fromJson(respuesta.data));

                    $scope.data={
                        paises: respuesta.data
                    };

                    
                    
              });
        };

        $scope.sendRegister = function() {
            if($scope.profileView.contrasena==$scope.profileView.contrasena2){
                var updateData = {
                    opcion:2, // modificación del perfil de usuario
                    id: $scope.profileView.id,
                    usuario: $scope.profileView.usuario,
                    nombre: $scope.profileView.nombre,
                    apellido1: $scope.profileView.apellido1,
                    apellido2: $scope.profileView.apellido2,
                    nacimiento: $scope.profileView.nacimiento,
                    pais: $scope.profileView.pais,
                    telefono: $scope.profileView.telefono,
                    direccion: $scope.profileView.direccion,
                    email: $scope.profileView.mail,
                    contrasenia: $scope.profileView.contrasena
                };

                $http.post("../dao/service/UsuarioService.php", angular.toJson(updateData))
                .then(function(respuesta){
                    /*
                        Esto se ejecuta si todo va bien. Podemos leer la respuesta
                        que nos dio el servidor accediendo a la propiedad data
                        Recordemos que tenemos que decodificarla, ya que si enviamos con JSON
                        deben respondernos con JSON (no es obligatorio, pero sí es una buena práctica)
                */
                    console.log("Petición terminada. La respuesta es: ", angular.fromJson(respuesta.data));

                    if(respuesta.data.response==true){
                        $scope.abrirVentanaModalOk(respuesta.data.mensaje);
                    }else{
                        $scope.abrirVentanaModalKo(respuesta.data.mensaje);
                    }
                });
            }
        };

        $scope.volver = function (){
            window.location.href="login.php";
        }

        // abre una ventana modal con operación OK
        $scope.abrirVentanaModalOk = function(message){
            $scope.params = {"title":"Registro","message":message};

            $uibModal.open({
              scope: $scope,
              templateUrl: '../php/modal/sucessModal.html',
              resolve: {
                params: function() {
                  return $scope.params;
                }
              }
            });
        };

        // abre una ventana modal con operación KO
        $scope.abrirVentanaModalKo = function(message){
            $scope.params = {"title":"Registro","message":message};

            $uibModal.open({
              scope: $scope,
              templateUrl: '../php/modal/errorModal.html',
              resolve: {
                params: function() {
                  return $scope.params;
                }
              }
            });
        };
        
    }]);

app.config(['$qProvider', function ($qProvider) {
    $qProvider.errorOnUnhandledRejections(false);
}]);