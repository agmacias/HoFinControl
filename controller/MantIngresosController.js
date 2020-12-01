app.controller('MantIngresosController', ['$scope','$http','$uibModal', function($scope,$http,$uibModal) {
        $scope.init = function(){
            // inicialización de variables
            $scope.mantIngresosView={};
            $scope.mantIngresosView.pantalla=0;
            $scope.operacion=1;
            $scope.numPerPage=5;
            $scope.data={
                ingresos:[]
            };
            $scope.mantIngresosIn={
                usuario: $scope.$parent.data
            };

            $scope.getCuentas();
            $scope.getTipoIngreso();
            $scope.getIngresosByUser();
        };

        $scope.changeToNew = function(){
            $scope.operacion=1;//alta            

            // limpiamos el formulario;
            $scope.mantIngresosView={                
                tipoIngreso:"",
                cuenta:"",
                pantalla:1
            };

            // establecemos el elemento por defecto
            angular.forEach($scope.data.cuentas,function(elto){
                if(elto.defecto==1){
                    $scope.mantIngresosView.cuenta = elto.id;
                }
            });
        }
        // obtiene un listado de cuentas según el usuario
        $scope.getCuentas = function(){
            var params = {
                opcion:1,
                usuario: $scope.mantIngresosIn.usuario.id
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
                            $scope.mantIngresosView.cuenta = elto.id;
                        }
                    });

              });
        };
        // obtiene un listado de cuentas según el usuario
        $scope.getTipoIngreso = function(){
            var params = {
                opcion:1,
                esIngreso: 1
            };

            $http.post("../dao/service/IngresoGastoService.php", angular.toJson(params))
            .then(function(respuesta){
                    /*
                        Esto se ejecuta si todo va bien. Podemos leer la respuesta
                        que nos dio el servidor accediendo a la propiedad data
                        Recordemos que tenemos que decodificarla, ya que si enviamos con JSON
                        deben respondernos con JSON (no es obligatorio, pero sí es una buena práctica)
                */
                    console.log("Petición terminada. La respuesta es: ", angular.fromJson(respuesta.data));

                    $scope.data.tipo_ingreso = respuesta.data;
                    // establecemos el elemento por defecto
                    $scope.mantIngresosView.tipoIngreso = "0";

              });
        };

        $scope.searchIngresoForm = function(){
            var params = {
                opcion:0,
                usuario: $scope.mantIngresosIn.usuario.id,
                isIngreso:1
            };

            if($scope.mantIngresosView.cuenta!='0'){
                params.cuenta = $scope.mantIngresosView.cuenta;
            }
            if($scope.mantIngresosView.tipoIngreso!='0'){
                params.tipo = $scope.mantIngresosView.tipoIngreso;
            }
            if($scope.mantIngresosView.fechaDesde!=undefined){
                params.fechaDesde = $scope.mantIngresosView.fechaDesde;
            }
            if($scope.mantIngresosView.fechaHasta!=undefined){
                params.fechaHasta = $scope.mantIngresosView.fechaHasta;
            }
            if($scope.mantIngresosView.asunto!=undefined){
                params.asunto = $scope.mantIngresosView.asunto;
            }

            $http.post("../dao/service/TransaccionesService.php", angular.toJson(params))
            .then(function(respuesta){
                    /*
                        Esto se ejecuta si todo va bien. Podemos leer la respuesta
                        que nos dio el servidor accediendo a la propiedad data
                        Recordemos que tenemos que decodificarla, ya que si enviamos con JSON
                        deben respondernos con JSON (no es obligatorio, pero sí es una buena práctica)
                */
                    console.log("Petición terminada. La respuesta es: ", angular.fromJson(respuesta.data));

                    $scope.data.ingresos = respuesta.data;
                    $scope.currentPage = 1;

                    $scope.range();

              });
        };

        $scope.getIngresosByUser = function(){

            var params = {
                opcion:1,
                usuario: $scope.mantIngresosIn.usuario.id,
                tipoIngreso: 1
            };

            $http.post("../dao/service/TransaccionesService.php", angular.toJson(params))
            .then(function(respuesta){
                    /*
                        Esto se ejecuta si todo va bien. Podemos leer la respuesta
                        que nos dio el servidor accediendo a la propiedad data
                        Recordemos que tenemos que decodificarla, ya que si enviamos con JSON
                        deben respondernos con JSON (no es obligatorio, pero sí es una buena práctica)
                */
                    console.log("Petición terminada. La respuesta es: ", angular.fromJson(respuesta.data));

                    $scope.data.ingresos = respuesta.data;
                    $scope.currentPage = 1;

                    $scope.range();

              });
            
        };

        $scope.sendIngresoForm = function(){
           if($scope.mantIngresosView.cuantia || $scope.operacion==3){
               var params = {
                    opcion: 2, // operación de alta
                    cuenta: $scope.mantIngresosView.cuenta,
                    usuario: $scope.mantIngresosIn.usuario.id,
                    tipo: $scope.mantIngresosView.tipoIngreso,
                    fecha: $scope.mantIngresosView.fecha,
                    asunto: $scope.mantIngresosView.asunto,
                    cuantia: parseFloat($scope.mantIngresosView.cuantia),
                    descripcion : $scope.mantIngresosView.descripcion
                };
                if($scope.operacion==2){
                    // si la operación es modificación
                    params.opcion=3;
                    params.id = $scope.mantIngresosView.id
                }else if($scope.operacion==3){
                    // si es operacion de eliminar
                    params = {
                        opcion:4,
                        id: $scope.mantIngresosView.id
                    }
                }

                $http.post("../dao/service/TransaccionesService.php", angular.toJson(params))
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

                        $scope.getIngresosByUser();
                        $scope.cleanForm();

                    }else{
                        $scope.abrirVentanaModalKo(respuesta.data.mensaje)
                    }

                });
           }
        };

        $scope.eliminarIngreso = function(seleccionado){
            $scope.mantIngresosView.id = seleccionado.id;
            $scope.operacion=3;
            $scope.sendIngresoForm();
        };
       
        //carga los valores en la pantalla de modificación
        $scope.modificarIngreso = function(seleccionado){
            $scope.mantIngresosView.id = seleccionado.id;
            $scope.mantIngresosView.cuenta = seleccionado.cuenta;
            $scope.mantIngresosView.tipoIngreso = seleccionado.tipo;
            $scope.mantIngresosView.fecha = formatDate(seleccionado.fecha);
            $scope.mantIngresosView.asunto = seleccionado.asunto;
            $scope.mantIngresosView.descripcion = seleccionado.descripcion;
            $scope.mantIngresosView.cuantia = parseFloat(seleccionado.cuantia);

            $scope.mantIngresosView.pantalla=1;
            $scope.operacion=2;
        };

        function formatDate(fecha){
            var fechaSplit = fecha.split("-");
            return new Date(fechaSplit[1]+"-"+fechaSplit[0]+"-"+fechaSplit[2]);
        }

        $scope.cleanForm = function(){
            // inicialización de variables
            $scope.mantIngresosView={};
            $scope.mantIngresosView.pantalla=0;
            $scope.operacion=1;

            // limpiamos el formulario;
            $scope.mantIngresosView={
                tipoIngreso:"0",
                pantalla:0
            };

            // establecemos el elemento por defecto
            angular.forEach($scope.data.cuentas,function(elto){
                if(elto.defecto==1){
                    $scope.mantIngresosView.cuenta = elto.id;
                }
            });
        };

        // abre una ventana modal con operación OK
        $scope.abrirVentanaModalOk = function(message){
            $scope.params = {"title":"Mantenimiento de ingresos","message":message};

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
            $scope.params = {"title":"Mantenimiento de ingresos","message":message};

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

        // paginación de la tabla
        $scope.paginate = function(value) {
            var begin, end, index;
            begin = ($scope.currentPage - 1) * $scope.numPerPage;
            end = begin + $scope.numPerPage;
            index = $scope.data.ingresos.indexOf(value);
            return (begin <= index && index < end);
        };

        $scope.nextPage = function () {
            if ($scope.currentPage < $scope.data.ingresos.length - 1) {
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
            var size = $scope.data.ingresos.length;
            var start = ($scope.currentPage - 1) * $scope.numPerPage;
            var end = Math.ceil(size/$scope.numPerPage);

            for (var i = start; i < end; i++) {
                $scope.ret.push(i);
            }
        };


    }]);

