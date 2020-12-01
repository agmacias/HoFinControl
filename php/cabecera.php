<div ng-controller="CabeceraController" ng-init="init()">
    
    <nav class="navbar navbar-default bg-primary navbar-absolute">
        <div class="container-fluid">
            <div>
                <div id="topnav" class="topnav">
                    <a href="#" ng-click="openNav()">
                        <svg width="30" height="30" id="icoOpen">
                            <path d="M0,5 30,5" stroke="#000" stroke-width="5"/>
                            <path d="M0,14 30,14" stroke="#000" stroke-width="5"/>
                            <path d="M0,23 30,23" stroke="#000" stroke-width="5"/>
                        </svg>
                    </a>
                </div>
                
                <a class="navbar-brand" href="#">{{cabeceraView.pantalla}}</a>
            </div>

            <ul class="nav justify-content-end">
                <li class="nav-item">
                    <a class="nav-link usuarioLink" href="#">Bienvenido, {{cabeceraView.nombre}} {{cabeceraView.apellido1}} {{cabeceraView.apellido2}}</a>
                    <div class="avatarDashboard">
                        <i class="fa fa-user"></i>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link salirLink" href="#" ng-click="cerrar()">Salir</a>
                    <div class="avatarClose">
                        <!--<i class="fa fa-times close" aria-hidden="true" title="cerrar" ng-click="cerrar()"></i>-->
                        <i class="fa fa-sign-out close" aria-hidden="true" title="Salir" ng-click="cerrar()"></i>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</div>