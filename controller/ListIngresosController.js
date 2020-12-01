app.controller('ListIngresosController', ['$scope','$http', function($scope,$http) {
        $scope.init = function(){
            $scope.listIngresosView={
                busqueda : 0
            };
            $scope.data = {};
            $scope.listIngresosIn={
                usuario: $scope.$parent.data
            };
            $scope.getCuentas();
            $scope.getTipoIngreso();
        }

        // obtiene un listado de cuentas según el usuario
        $scope.getCuentas = function(){
            var params = {
                opcion:1,
                usuario: $scope.listIngresosIn.usuario.id
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

              });
        };

        $scope.findResults = function() {
            var loginIn = {
                opcion: 8,
                usuario: $scope.listIngresosIn.usuario.id,
                cuenta: $scope.listIngresosView.cuenta,
                tipo: $scope.listIngresosView.tipoIngreso,
                fechaDesde : $scope.listIngresosView.fechaDesde,
                fechaHasta : $scope.listIngresosView.fechaHasta,
                groupBy: $scope.listIngresosView.agrupar,
                isIngreso:1
            };

            $http.post("../dao/service/TransaccionesService.php", angular.toJson(loginIn))
            .then(function(respuesta){
                /*
                    Esto se ejecuta si todo va bien. Podemos leer la respuesta
                    que nos dio el servidor accediendo a la propiedad data
                    Recordemos que tenemos que decodificarla, ya que si enviamos con JSON
                    deben respondernos con JSON (no es obligatorio, pero sí es una buena práctica)
            */
                console.log("Petición terminada. La respuesta es: ", angular.fromJson(respuesta.data));

                $scope.listIngresosView.busqueda = 1;

                if($scope.listIngresosView.agrupar=="cuenta" ||
                $scope.listIngresosView.agrupar=="gasto"){
                    var agrupado = generateArrayToList(respuesta.data);
                    $scope.data.gastos = agrupado;
                }else{
                    $scope.data.gastos=[{
                            datos: respuesta.data
                        }
                    ];
                }
                //$scope.data.gastos = respuesta.data;

            });
        };

        // función que nos ayuda a agrupar los resultados obtenidos de la consulta
        function generateArrayToList(datos){
            var arrayDatos = [];
            var arrayDatosGroup = [];
            var idCuentaAnterior = null;
            var nombreCuentaAnterior = null;
            var idGastoAnterior = null;
            var nombreGastoAnterior = null;
            angular.forEach(datos,function(elto){
                if($scope.listIngresosView.agrupar=="cuenta"){
                    if(elto.cuenta!=idCuentaAnterior && idCuentaAnterior!=null){
                        arrayDatosGroup.push({groupBy:nombreCuentaAnterior,datos:arrayDatos});
                        arrayDatos=[];
                        arrayDatos.push(elto);
                        nombreCuentaAnterior = elto.nombre_cuenta;
                        idCuentaAnterior = elto.cuenta;
                    }else{
                        arrayDatos.push(elto);
                        nombreCuentaAnterior = elto.nombre_cuenta;
                        idCuentaAnterior = elto.cuenta;
                    }
                }else if($scope.listIngresosView.agrupar=="gasto"){
                    if(elto.tipo!=idGastoAnterior && idGastoAnterior!=null){
                        arrayDatosGroup.push({groupBy:nombreGastoAnterior,datos:arrayDatos});
                        arrayDatos=[];
                        arrayDatos.push(elto);
                        nombreGastoAnterior = elto.tipo_nombre;
                        idGastoAnterior = elto.tipo;
                    }else{
                        arrayDatos.push(elto);
                        nombreGastoAnterior = elto.tipo_nombre;
                        idGastoAnterior = elto.tipo;
                    }
                }
            });

            if($scope.listIngresosView.agrupar=="gasto"){
                arrayDatosGroup.push({groupBy:nombreGastoAnterior,datos:arrayDatos});
            }else{
                arrayDatosGroup.push({groupBy:nombreCuentaAnterior,datos:arrayDatos});
            }
            

            return arrayDatosGroup;
        }
        $scope.exportar = function (){
            $("#table2excel").table2excel({
	    // exclude CSS class
	    exclude: false,
	    name: "Listado de ingresos",
	    filename: "ingresos", //do not include extension
	    fileext: ".xls" // file extension
	  });
        };

    }]);

