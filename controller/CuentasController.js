app.controller('CuentasController', ['$scope','$http','$uibModal', function($scope,$http,$uibModal) {
        $scope.init = function(){
            $scope.data={};
            $scope.cuentasView={pantalla:"0"};
            $scope.operacion=1;//alta

            $scope.cuentasIn={
                usuario:$scope.$parent.data
            };
            // cargamos el select de tipos de cuentas           
            // cargamos el listado de cuentas
            $scope.getCuentas();
        };

        // cambiar la funcionalidad de la pantalla en función del botón seleccionado
        $scope.changeToNew = function(){
            $scope.operacion=1;//alta            

            // limpiamos el formulario;
            var defecto = $scope.cuentasView.defecto;
            $scope.cuentasView={
                defecto : defecto,
                pantalla:1
            };
        }

        // obtiene un listado de cuentas según el usuario
        $scope.getCuentas = function(){
            var params = {
                opcion:1,
                usuario: $scope.cuentasIn.usuario.id
            };
            
            $http.post("../dao/service/CuentasService.php", angular.toJson(params))
            .then(function(respuesta){
                    /*
                        Esto se ejecuta si todo va bien. Podemos leer la respuesta
                        que nos dio el servidor accediendo a la propiedad data
                        Recordemos que tenemos que decodificarla, ya que si enviamos con JSON
                        deben respondernos con JSON (no es obligatorio, pero sí es una buena práctica)
                */
                    console.log("Petición terminada. La respuesta es: ", angular.fromJson(respuesta.data));

                    $scope.data.cuentas = respuesta.data;
                    // establecemos el elemento por defecto
                    angular.forEach($scope.data.cuentas,function(elto){
                        if(elto.defecto==1){
                            $scope.cuentasView.defecto = elto.id;
                        }
                    });
                    
                    $scope.totalItems = $scope.data.cuentas.length;
                    $scope.currentPage = 1;
                    $scope.numPerPage = 5;

                    $scope.range();
                    
              });
            
        };

        // función que elimina una cuenta
        $scope.eliminarCuenta = function(cuenta){
            var params = {
                opcion:5,
                id: cuenta.id
            };
            
            $http.post("../dao/service/CuentasService.php", angular.toJson(params))
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
                    $scope.getCuentas();
                }else{
                    $scope.abrirVentanaModalKo(respuesta.data.mensaje)
                }
                
              });
        }

        // función para dar de alta una cuenta
        $scope.sendCuentasForm = function() {
            var params = {
                opcion: 2, // operación de alta
                nombre: $scope.cuentasView.nombre,
                usuario: $scope.cuentasIn.usuario.id,                
                cuantia: $scope.cuentasView.cuantia==null?0:$scope.cuentasView.cuantia,
                fecha: $scope.cuentasView.fecha,
                fecha_fin : $scope.cuentasView.fecha_fin,
                defecto:0
            };

            if($scope.operacion==2){
                // si la operación es modificación
                params.opcion=3;
                params.id = $scope.cuentasView.id;
                params.defecto = $scope.cuentasView.defectoMod;
            }

            $http.post("../dao/service/CuentasService.php", angular.toJson(params))
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

                    $scope.getCuentas();
                    
                }else{
                    $scope.abrirVentanaModalKo(respuesta.data.mensaje)
                }
                
            });
        };

        // función encargada de llamar al servicio que actualiza la cuenta a defecto
        $scope.marcarDefecto = function (cuenta){            
             var params = {
                opcion: 4, // operación de modificación campo defecto
                id:cuenta.id,
                nombre: cuenta.nombre,
                usuario: $scope.cuentasIn.usuario.id,                
                cuantia: cuenta.cuantia==null?0:cuenta.cuantia,
                fecha: cuenta.fecha,
                fecha_fin : cuenta.f_fin,
                defecto:1
            };

            $scope.updateCuenta(params);
        };

        // función para actualizar la información de la cuenta
        $scope.updateCuenta= function(params){

            $http.post("../dao/service/CuentasService.php", angular.toJson(params))
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

        // función encargada de llamar al servicio necesario para modificar la información de la cuenta
        $scope.modificarCuenta = function(datos){
            var defecto = $scope.cuentasView.defecto;
            $scope.cuentasView={
                id: datos.id,
                nombre: datos.nombre,                
                cuantia: parseFloat(datos.cuantia),
                fecha: formatDate(datos.fecha),
                fechaFin: datos.fechaFin!=null?formatDate(datos.fechaFin):null,
                defecto: defecto,
                defectoMod: datos.defecto
            };

            if(datos.fechaFin!=null){
                $scope.cuentasView.fechaFin = new Date(datos.fechaFin);
            }

            $scope.operacion=2;//modificación
            $scope.cuentasView.pantalla=1;
        };

        function formatDate(fecha){
            var fechaSplit = fecha.split("-");
            return new Date(fechaSplit[1]+"-"+fechaSplit[0]+"-"+fechaSplit[2]);
        }

        // función encargada de llamar al servicio encargado de buscar información de la cuenta
        $scope.searchCuentas = function(){
            var params={
                opcion:6,
                usuario: $scope.cuentasIn.usuario.id,
                nombre: $scope.cuentasView.nombre==""?null:$scope.cuentasView.nombre,
                cuantiaDesde: $scope.cuentasView.cuantiaDesde,
                cuantiaHasta: $scope.cuentasView.cuantiaHasta,
                fechaDesde: $scope.cuentasView.fechaDesde,
                fechaHasta: $scope.cuentasView.fechaHasta
            };
            $http.post("../dao/service/CuentasService.php", angular.toJson(params))
            .then(function(respuesta){
                /*
                    Esto se ejecuta si todo va bien. Podemos leer la respuesta
                    que nos dio el servidor accediendo a la propiedad data
                    Recordemos que tenemos que decodificarla, ya que si enviamos con JSON
                    deben respondernos con JSON (no es obligatorio, pero sí es una buena práctica)
            */
                console.log("Petición terminada. La respuesta es: ", angular.fromJson(respuesta.data));

                $scope.data.cuentas = respuesta.data;
                    // establecemos el elemento por defecto
                    angular.forEach($scope.data.cuentas,function(elto){
                        if(elto.defecto==1){
                            $scope.cuentasView.defecto = elto.id;
                        }
                    });

                    $scope.totalItems = $scope.data.cuentas.length;
                    $scope.currentPage = 1;
                    $scope.numPerPage = 5;

                    $scope.range();

            });
        };

        // abre una ventana modal con operación OK
        $scope.abrirVentanaModalOk = function(message){
            $scope.params = {"title":"Cuentas","message":message};

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
            $scope.params = {"title":"Cuentas","message":message};

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
            index = $scope.data.cuentas.indexOf(value);
            return (begin <= index && index < end);
        };

        $scope.nextPage = function () {
            if ($scope.currentPage < $scope.data.cuentas.length - 1) {
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
            var size = $scope.data.cuentas.length;
            var start = ($scope.currentPage - 1) * $scope.numPerPage;
            var end = Math.ceil(size/$scope.numPerPage);

            for (var i = start; i < end; i++) {
                $scope.ret.push(i);
            }
        };
}]);


