app.controller('PermisosController', ['$scope','$http','$uibModal', function($scope,$http,$uibModal) {
        $scope.init = function(){
            $scope.data={};
            $scope.permisosView={pantalla:"0"};
            $scope.operacion=1;//alta

            $scope.permisosIn={
                usuario:$scope.$parent.data
            };
            // cargamos el select de tipos de cuentas
            // cargamos el listado de cuentas
            $scope.getUsuarios();
        };

        // cambiar la funcionalidad de la pantalla en función del botón seleccionado
        $scope.changeToNew = function(){
            $scope.operacion=1;//alta

            // limpiamos el formulario;
            var defecto = $scope.permisosView.defecto;
            $scope.permisosView={
                defecto : defecto,
                pantalla:1
            };
        }

        // obtiene un listado de cuentas según el usuario
        $scope.getUsuarios = function(){
            var params = {
                opcion:5,
                usuario: $scope.permisosView.usuario,
                nombre: $scope.permisosView.nombre,
                apellido: $scope.permisosView.apellido1,
                apellido2: $scope.permisosView.apellido2,
                fechaDesde: $scope.permisosView.fechaDesde,
                fechaHasta: $scope.permisosView.fechaHasta
                //usuario: $scope.permisosIn.usuario.id
            };

            $http.post("../dao/service/UsuarioService.php", angular.toJson(params))
            .then(function(respuesta){
                    /*
                        Esto se ejecuta si todo va bien. Podemos leer la respuesta
                        que nos dio el servidor accediendo a la propiedad data
                        Recordemos que tenemos que decodificarla, ya que si enviamos con JSON
                        deben respondernos con JSON (no es obligatorio, pero sí es una buena práctica)
                */
                    console.log("Petición terminada. La respuesta es: ", angular.fromJson(respuesta.data));

                    $scope.data.usuarios = respuesta.data;

                    $scope.totalItems = $scope.data.usuarios.length;
                    $scope.currentPage = 1;
                    $scope.numPerPage = 5;

                    $scope.range();

              });

        };

        // consultamos los permisos disponibles
        $scope.getPermisos = function(option){
            var params = {
                opcion:2
            };

            $scope.operacion=2;//modificación
            $scope.permisosView.pantalla=1;

            $scope.permisosView.userSelect = option.nombre.concat(" ").concat(option.apellido1).concat(" ").concat(option.apellido2);
            $scope.permisosView.idUser = option.id;

            $http.post("../dao/service/PermisosService.php", angular.toJson(params))
            .then(function(respuesta){
                    /*
                        Esto se ejecuta si todo va bien. Podemos leer la respuesta
                        que nos dio el servidor accediendo a la propiedad data
                        Recordemos que tenemos que decodificarla, ya que si enviamos con JSON
                        deben respondernos con JSON (no es obligatorio, pero sí es una buena práctica)
                */
                    console.log("Petición terminada. La respuesta es: ", angular.fromJson(respuesta.data));

                    $scope.getPermisosDestino(respuesta.data,option.id);

              });            
        };

        // obtiene los permisos asociados al usuario
        $scope.getPermisosDestino = function (permisosOrigen,idUsuario){

            var params = {
                opcion:1,
                usuario : idUsuario
            };

            $http.post("../dao/service/PermisosService.php", angular.toJson(params))
            .then(function(respuesta){
                    /*
                        Esto se ejecuta si todo va bien. Podemos leer la respuesta
                        que nos dio el servidor accediendo a la propiedad data
                        Recordemos que tenemos que decodificarla, ya que si enviamos con JSON
                        deben respondernos con JSON (no es obligatorio, pero sí es una buena práctica)
                */
                    console.log("Petición terminada. La respuesta es: ", angular.fromJson(respuesta.data));

                    $scope.data.permisosOrigen = [];

                    $scope.data.permisosDestino = [];

                    angular.forEach(respuesta.data,function(elto){
                        //$scope.data.permisosDestino.push({id:elto.id,permiso:elto.permiso,nombre:elto.nombre});
                        $scope.data.permisosDestino.push({id:elto.permiso,permiso:elto.permiso,nombre:elto.nombre});
                    });

                    // nos recorremos los permisos en origen para no añadir los que estén en destino
                    angular.forEach(permisosOrigen,function(elto){
                        var existe = false;
                        angular.forEach(respuesta.data,function(elto2){
                            if(elto.id==elto2.permiso){
                                existe = true;
                            }
                        });

                        if(!existe){
                            $scope.data.permisosOrigen.push({id:elto.id,permiso:elto.permiso,nombre:elto.nombre});
                        }
                    });

              });
        };

        $scope.asignTo = function(){
            var permisosOrigenAux = [];
            if(!$scope.data.permisosDestino){
                $scope.data.permisosDestino=[];
            }
            angular.forEach($scope.permisosView.permisosOrigen,function(origen){
                $scope.data.permisosDestino.push(JSON.parse(origen));
                
            });

            angular.forEach($scope.data.permisosOrigen,function(eltoOrigen){
                var existe = false;
                angular.forEach($scope.data.permisosDestino,function(destino){
                    if(eltoOrigen.id==destino.id){
                        existe=true;
                    }
                });

                if(!existe){
                    permisosOrigenAux.push(eltoOrigen);
                }
             });

            $scope.data.permisosOrigen = permisosOrigenAux;
        };

        $scope.unAsignTo = function(){
            var permisosDestinoAux = [];
            if(!$scope.data.permisosOrigen){
                $scope.data.permisosOrigen=[];
            }
            angular.forEach($scope.permisosView.permisosDestino,function(destino){
                $scope.data.permisosOrigen.push(JSON.parse(destino));                
            });
            
            angular.forEach($scope.data.permisosDestino,function(eltoDestino){
                var existe = false;
                angular.forEach($scope.data.permisosOrigen,function(origen){
                    if(eltoDestino.id==origen.id){
                        existe=true;
                    }
                });

                if(!existe){
                    permisosDestinoAux.push(eltoDestino);
                }
            });

            $scope.data.permisosDestino = permisosDestinoAux;
        };

        $scope.searchUsuarios = function(){
            var params={
                opcion:5,
                usuario: $scope.permisosView.usuario,
                nombre: $scope.permisosView.nombre==""?null:$scope.permisosView.nombre,
                apellido: $scope.permisosView.apellido1==""?null:$scope.permisosView.apellido1,
                apellido2: $scope.permisosView.apellido2==""?null:$scope.permisosView.apellido2,
                fechaDesde: $scope.permisosView.fechaDesde,
                fechaHasta: $scope.permisosView.fechaHasta
            };
            $http.post("../dao/service/UsuarioService.php", angular.toJson(params))
            .then(function(respuesta){
                /*
                    Esto se ejecuta si todo va bien. Podemos leer la respuesta
                    que nos dio el servidor accediendo a la propiedad data
                    Recordemos que tenemos que decodificarla, ya que si enviamos con JSON
                    deben respondernos con JSON (no es obligatorio, pero sí es una buena práctica)
            */
                console.log("Petición terminada. La respuesta es: ", angular.fromJson(respuesta.data));

                $scope.data.usuarios = respuesta.data;
                    
                $scope.totalItems = $scope.data.usuarios.length;
                $scope.currentPage = 1;
                $scope.numPerPage = 5;

                $scope.range();

            });
        };

        $scope.savePermisos = function (){
            var params = {
                opcion: 3,
                usuario: $scope.permisosView.idUser,
                permisos: $scope.data.permisosDestino
            };
            $http.post("../dao/service/PermisosService.php", angular.toJson(params))
            .then(function(respuesta){
                /*
                    Esto se ejecuta si todo va bien. Podemos leer la respuesta
                    que nos dio el servidor accediendo a la propiedad data
                    Recordemos que tenemos que decodificarla, ya que si enviamos con JSON
                    deben respondernos con JSON (no es obligatorio, pero sí es una buena práctica)
            */
                console.log("Petición terminada. La respuesta es: ", angular.fromJson(respuesta.data));

                if(respuesta.data.response==true){
                    $scope.abrirVentanaModalOk(respuesta.data.mensaje)
                }else{
                    $scope.abrirVentanaModalKo(respuesta.data.mensaje)
                }
            });
        };

        // abre una ventana modal con operación OK
        $scope.abrirVentanaModalOk = function(message){
            $scope.params = {"title":"Gestión de permisos","message":message};

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
            $scope.params = {"title":"Gestión de permisos","message":message};

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

        $scope.paginate = function(value) {
            var begin, end, index;
            begin = ($scope.currentPage - 1) * $scope.numPerPage;
            end = begin + $scope.numPerPage;
            index = $scope.data.usuarios.indexOf(value);
            return (begin <= index && index < end);
        };

        $scope.nextPage = function () {
            if ($scope.currentPage < $scope.data.usuarios.length - 1) {
                $scope.currentPage++;
            }
        };

        $scope.prevPage = function () {
            if ($scope.currentPage > 0) {
                $scope.currentPage--;
            }
        };

        $scope.setPage = function (n) {
            $scope.currentPage = n;
        };

        // calculamos los números de páginas
        $scope.range = function () {
            $scope.ret = [];
            var size = $scope.data.usuarios.length;
            var start = ($scope.currentPage - 1) * $scope.numPerPage;
            var end = Math.ceil(size/$scope.numPerPage);

            for (var i = start; i < end; i++) {
                $scope.ret.push(i);
            }
        };
}]);


