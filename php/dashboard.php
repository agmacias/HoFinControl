
<div ng-controller="DashBoardController" ng-init="init()">
    <div class="content">
        <div class="container-fluid">
            <!-- inicio tarjetas -->
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-margin">
                        <div class="card-header">
                            <p><i class="fa fa-list-alt" aria-hidden="true"></i>
                                <strong>Cuenta</strong>
                            </p>
                        </div>
                        <div class="card-content">
                            <div class="{{dashboardView.styleCuentaSpinner}}"></div>
                            {{dashboardView.nombreCuenta}}                            
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-margin">
                        <div class="card-header">
                            <p><i class="fa fa-bar-chart" aria-hidden="true"></i>
                                <strong>Total cuenta</strong>
                            </p>
                        </div>
                        <div class="card-content">
                            <div class="{{dashboardView.styleTotalCuentaSpinner}}"></div>
                            {{dashboardView.total}}
                            <i class="fa fa-eur" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-margin">
                        <div class="card-header">
                            <p><i class="fa fa-arrow-down" aria-hidden="true"></i>
                                <strong>Total gastos</strong>
                            </p>
                        </div>
                        <div class="card-content">
                            <div id="totalGastosSpinner" class="{{dashboardView.styleTotalGastosSpinner}}"></div>
                            {{dashboardView.total_gastos}}
                            <i class="fa fa-eur" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-margin">
                        <div class="card-header">
                            <p><i class="fa fa-arrow-up" aria-hidden="true"></i>
                                <strong>Total ingresos</strong>
                            </p>
                        </div>
                        <div class="card-content">
                            <div class="{{dashboardView.styleTotalIngresosSpinner}}"></div>
                            {{dashboardView.total_ingresos}}
                            <i class="fa fa-eur" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fin tarjetas -->
            <!-- Inicio Gráficos-->
            <div class="row">
                <div class="col-md-7">
                    <div class="card" style="min-height: 350px;">
                        <div class="card-header">
                            <p><i class="fa fa-line-chart" aria-hidden="true"></i>
                                <strong>Fluctuaciones anual</strong>
                            </p>
                        </div>
                        <div class="card-content">
                            <div class="{{dashboardView.styleFluctuacionesAnual}}"></div>
                            <canvas
                                class="chart chart-line"
                                chart-data="datos"
                                chart-labels="etiquetas"
                                chart-series="series"
                                chart-legend="legend"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card" style="min-height: 360px;">
                        <div class="card-header">
                            <p><i class="fa fa-pie-chart" aria-hidden="true"></i>
                                <strong>Gastos anual</strong>
                            </p>
                        </div>
                        <div class="card-content">
                            <div class="{{dashboardView.styleGastosAnual}}"></div>
                            <canvas class="chart chart-doughnut" chart-data="dataPie" chart-labels="etiquetasPie"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fin Gráficos -->
            <!-- Inicio listados -->
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <p><i class="fa fa-check" aria-hidden="true"></i>
                                <strong>Últimas transacciones</strong>
                            </p>
                        </div>
                        <div class="card-content">
                            <table class="table" style="text-align: left">
                                <thead>
                                <th>
                                    Cuenta
                                </th>
                                <th>
                                    Asunto
                                </th>
                                <th>
                                    Cuantía
                                </th>
                                <th>
                                    Fecha
                                </th>
                                </thead>

                                <tbody>
                                    <tr ng-if="dashboardView.transacciones.length>0" ng-repeat="option in dashboardView.transacciones">
                                        <td>{{option.nombre_cuenta}}</td>
                                        <td>{{option.asunto}}</td>
                                        <td>{{option.cuantia}}</td>
                                        <td>{{option.fecha | date:"MM/dd/yyyy"}}</td>                                        
                                    </tr>
                                    <tr ng-if="dashboardView.transacciones.length==0">
                                        <td colspan="6">No hay transacciones registradas.</td>
                                    </tr>                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>                
            </div>
            <!-- Fin listados -->
        </div>
    </div>

</div>
