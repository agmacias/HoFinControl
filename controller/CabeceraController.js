app.controller('CabeceraController', ['$scope','$http', function($scope,$http) {
        $scope.init = function(){
            //$scope.getUsuarioLogado();
            $scope.cabeceraView = $scope.$parent.$parent.$parent.cabeceraData;
        }        
        $scope.cerrar = function(){
            $http.post("logoff.php", angular.toJson({}))
            .then(function(respuesta){
                 /*
                    Esto se ejecuta si todo va bien. Podemos leer la respuesta
                    que nos dio el servidor accediendo a la propiedad data
                    Recordemos que tenemos que decodificarla, ya que si enviamos con JSON
                    deben respondernos con JSON (no es obligatorio, pero sí es una buena práctica)
            */
                console.log("Petición terminada. La respuesta es: ", angular.fromJson(respuesta.data));

                window.location.href=angular.fromJson(respuesta.data);

            });
        }
        
    }]);