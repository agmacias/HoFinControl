// si no le metemos módulos adicionales irá sin corchetes
angular.module('app')
    .controller('ControladorController', ['$scope','$http', function($scope,$http) {
        $scope.init = function(){
            $scope.pushListado = false;
            $scope.opcion=1; // dashboard
            $scope.getUsuarioLogado();
        };

        $scope.getUsuarioLogado = function(){
            var datos = {
                opcion:4
            }
            // cargamos el combo de paises
            $http.post("../dao/service/UsuarioService.php", angular.toJson(datos))
            .then(function(respuesta){
                    /*
                        Esto se ejecuta si todo va bien. Podemos leer la respuesta
                        que nos dio el servidor accediendo a la propiedad data
                        Recordemos que tenemos que decodificarla, ya que si enviamos con JSON
                        deben respondernos con JSON (no es obligatorio, pero sí es una buena práctica)
                */
                    console.log("Petición terminada. La respuesta es: ", angular.fromJson(respuesta.data));

                    $scope.cabeceraView={
                        id: respuesta.data.id,
                        nombre:respuesta.data.nombre,
                        apellido1:respuesta.data.apellido1,
                        apellido2:respuesta.data.apellido2,
                        nacimiento:respuesta.data.f_nacimiento,
                        pais:respuesta.data.pais,
                        telefono:respuesta.data.telefono,
                        direccion:respuesta.data.direccion,
                        mail:respuesta.data.mail,
                        contrasena : respuesta.data.password,
                        usuario : respuesta.data.usuario
                    }

                    // paso de parámetros a través de las diferentes directivas
                    $scope.cabeceraData = $scope.cabeceraView;
                    $scope.cabeceraData.pantalla="DashBoard";
                    $scope.cuentasData = $scope.cabeceraView;
                    $scope.ingresosData = $scope.cabeceraView;
                    $scope.gastosData = $scope.cabeceraView;
                    $scope.dashboardData = $scope.cabeceraView;
                    $scope.permisosData = $scope.cabeceraView;
                    $scope.listadosData = $scope.cabeceraView;

                    $scope.getPermisosUsuario();

              });
        };

        $scope.getPermisosUsuario = function(){
            var params = {
                opcion:1,
                usuario : $scope.cabeceraView.id
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

                    $scope.data = {};
                    $scope.data.permisosUsuario = respuesta.data;

                    angular.forEach(respuesta.data,function(perm){
                        if(perm.permiso==1){
                            $scope.mostrarPermiso1 =true;
                        }else if(perm.permiso==2){
                            $scope.mostrarPermiso2 =true;
                        }else if(perm.permiso==3){
                            $scope.mostrarPermiso3 =true;
                        }else if(perm.permiso==4){
                            $scope.mostrarPermiso4 =true;
                        }else if(perm.permiso==5){
                            $scope.mostrarPermiso5 =true;
                        }else if(perm.permiso==6){
                            $scope.mostrarPermiso6 =true;
                        }else if(perm.permiso==7){
                            $scope.mostrarPermiso7 =true;
                        }
                    })
              });
        };

        $scope.selectOptionMenu = function(option){
            if(option==1){
                $scope.cabeceraData.pantalla = "DashBoard";
            }else if(option==2){
                $scope.cabeceraData.pantalla = "Perfil";
            }else if(option==3){
                $scope.cabeceraData.pantalla = "Cuentas";
            }else if(option==4){
                $scope.cabeceraData.pantalla = "Gestión de permisos";
            }else if(option==5){
                $scope.cabeceraData.pantalla = "Mantenimiento de gastos";
            }else if(option==6){
                $scope.cabeceraData.pantalla = "Mantenimiento de ingresos";
            }else if(option==7){
                $scope.cabeceraData.pantalla = "Listados";
            }else if(option==8){
                $scope.cabeceraData.pantalla = "Listados >> Listado de gastos";
            }else if(option==9){
                $scope.cabeceraData.pantalla = "Listados >> Listado de ingresos";
            }

            $scope.opcion = option;
        }

        $scope.selectListado = function(){
            $scope.pushListado = !$scope.pushListado;
        };

        // función que nos vale para ocultar el menú lateral.
        $scope.closeNav=function() {            
            document.getElementById("sideNavigation").style.display = "none";
            document.getElementById("panelPrincipal").style.width = "100%";
            document.getElementById("topnav").style.display = "inline";
        }
    }]);

app.directive("cabecera", function() {
    return {
        restrict: 'E',
        scope: {
            data: '='            
        },
        templateUrl: '../php/cabecera.php'
    }
});

angular.module("app").directive("dashBoard", function() {
    return {
        restrict: 'E',
        scope: {
            data: '='
        },
        templateUrl: '../php/dashboard.php'
    }
});

angular.module("app").directive("profile", function() {
    return {
        restrict: 'E',
        scope: {
            data: '=',
            cabecera: '='
        },
        templateUrl: '../php/profile.php'
    }
});

angular.module("app").directive("cuentas", function() {
    return {
        restrict: 'E',
        scope: {
            data: '='
        },
        templateUrl: '../php/cuentas.php'
    }
});

angular.module("app").directive("ingresos", function() {
    return {
        restrict: 'E',
        scope: {
            data: '='
        },
        templateUrl: '../php/mantIngresos.php'
    }
});

angular.module("app").directive("gastos", function() {
    return {
        restrict: 'E',
        scope: {
            data: '='
        },
        templateUrl: '../php/mantGastos.php'
    }
});

angular.module("app").directive("permisos", function() {
    return {
        restrict: 'E',
        scope: {
            data: '='
        },
        templateUrl: '../php/permisos.php'
    }
});

angular.module("app").directive("listgastos", function() {
    return {
        restrict: 'E',
        scope: {
            data: '='
        },
        templateUrl: '../php/listGastos.php'
    }
});

angular.module("app").directive("listingresos", function() {
    return {
        restrict: 'E',
        scope: {
            data: '='
        },
        templateUrl: '../php/listIngresos.php'
    }
});