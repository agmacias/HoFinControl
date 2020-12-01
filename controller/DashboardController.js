app.controller('DashBoardController', ['$scope','$http', function($scope,$http) {
        var meses = {
            "1": "Enero",
            "2": "Febrero",
            "3": "Marzo",
            "4": "Abril",
            "5": "Mayo",
            "6": "Junio",
            "7": "Julio",
            "8": "Agosto",
            "9": "Septiembre",
            "10": "Octubre",
            "11": "Noviembre",
            "12": "Diciembre"
        };
        $scope.init = function(){
            $scope.dashboardView={                
                transacciones:[],                
                styleCuentaSpinner:"spinner-border text-primary",
                styleTotalCuentaSpinner:"spinner-border text-primary",
                styleTotalGastosSpinner:"spinner-border text-primary",
                styleTotalIngresosSpinner:"spinner-border text-primary",
                styleFluctuacionesAnual:"spinner-border text-primary",
                styleGastosAnual:"spinner-border text-primary",
            };
            $scope.dashboardIn={
                usuario: $scope.$parent.data
            };

            $scope.getCuentas();
            $scope.getUltimasTransacciones();
           
        };

        // obtiene los totales de los gastos e ingresos
        $scope.getTotalGastosIngresos = function(totalCuenta,cuenta){

            var params = {
                opcion:5,
                usuario: $scope.dashboardIn.usuario.id,
                cuenta: cuenta
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

                    $scope.dashboardView.total_gastos = respuesta.data[0].total_gasto==null?0:respuesta.data[0].total_gasto;
                    $scope.dashboardView.total_ingresos = respuesta.data[0].total_ingreso==null?0:respuesta.data[0].total_ingreso;

                    // total en cuenta
                    $scope.dashboardView.total = ((totalCuenta - parseFloat($scope.dashboardView.total_gastos)) + parseFloat($scope.dashboardView.total_ingresos)).toFixed(2);

                    $scope.dashboardView.styleTotalCuentaSpinner="";
                    $scope.dashboardView.styleTotalGastosSpinner="";
                    $scope.dashboardView.styleTotalIngresosSpinner="";

              });
        };
        // obtiene un listado de cuentas según el usuario
        $scope.getCuentas = function(){
            var params = {
                opcion:1,
                usuario: $scope.dashboardIn.usuario.id
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
                    var totalCuenta = 0;
                    var cuenta=null;
                    angular.forEach($scope.data.cuentas,function(elto){
                        if(elto.defecto==1){
                            cuenta = elto.id;
                            totalCuenta = parseFloat(elto.cuantia);
                            $scope.dashboardView.nombreCuenta = elto.nombre;                            
                        }
                    });

                    $scope.dashboardView.styleCuentaSpinner="";

                    if(cuenta!=null){
                        $scope.getTotalGastosIngresos(totalCuenta,cuenta);
                        $scope.getFluctuacionesAnual(cuenta);
                        $scope.getGastosAnual(cuenta);
                    }else{
                        $scope.dashboardView.nombreCuenta="-"
                        $scope.dashboardView.total=0;
                        $scope.dashboardView.total_gastos=0;
                        $scope.dashboardView.total_ingresos=0;
                        $scope.dashboardView.styleTotalCuentaSpinner="";
                        $scope.dashboardView.styleTotalGastosSpinner="";
                        $scope.dashboardView.styleTotalIngresosSpinner="";
                        $scope.dashboardView.styleFluctuacionesAnual="";
                        $scope.dashboardView.styleGastosAnual="";
                    }
              });
        };

        // realizará una llamada a TransaccionesService para obtener los gastos según el año
        $scope.getFluctuacionesAnual= function(cuenta){
            var fecha = new Date();
            var params = {
                opcion:6,
                usuario: $scope.dashboardIn.usuario.id,
                anio: fecha.getFullYear(),
                cuenta: cuenta
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

                    // montamos las estructuras para el gráfico de fluctuaciones
                    $scope.series = ['Ingresos', 'Gastos'];
                    $scope.datos = [];
                    var arrayIngresos=[];
                    var arrayGastos=[];
                    var labels={};
                    var sumatorioIngreso={};
                    var sumatorioGastos={};

                    $scope.series=[];
                    $scope.etiquetas=[];

                    angular.forEach(respuesta.data,function(elto){
                        labels[elto.mes]=elto.mes;
                        if(elto.isIngreso=="1"){
                            sumatorioIngreso[elto.mes]=elto.cuantia;
                        }else{
                            sumatorioGastos[elto.mes]=elto.cuantia;
                        }
                    });

                    for (var i in labels) {
                        $scope.etiquetas.push(meses[i]);
                    }

                    for (var i in sumatorioIngreso) {
                        arrayIngresos.push(sumatorioIngreso[i]);
                    }

                    for (var i in sumatorioGastos) {
                        arrayGastos.push(sumatorioGastos[i]);
                    }

                    $scope.datos.push(arrayIngresos);
                    $scope.datos.push(arrayGastos);

                    $scope.dashboardView.styleFluctuacionesAnual="";

              });
        };

        // obtiene los gastos anuales de un usuario agrupados por tipo de gasto
        $scope.getGastosAnual= function(cuenta){
            var fecha = new Date();
            var params = {
                opcion:7,
                usuario: $scope.dashboardIn.usuario.id,
                anio: fecha.getFullYear(),
                cuenta: cuenta
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

                    $scope.etiquetasPie = [];
                    $scope.dataPie=[];
                    // montamos las estructuras para el gráfico de fluctuaciones
                    angular.forEach(respuesta.data,function(elto){
                        $scope.etiquetasPie.push(elto.nombre);
                        $scope.dataPie.push(elto.cuantia);
                    });

                    $scope.dashboardView.styleGastosAnual="";
              });
        };

        $scope.getUltimasTransacciones = function(){
            var params = {
                opcion:0,
                usuario: $scope.dashboardIn.usuario.id,
                limit: 5
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
                    
                    $scope.dashboardView.transacciones= respuesta.data;
              });
        }
        
  }]);