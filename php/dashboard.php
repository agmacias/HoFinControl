
<div ng-controller="DashBoardController" ng-init="init()">
    <div class="content">
        <div class="container-fluid">
            <!-- inicio tarjetas -->
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-margin">
                        <div class="card-header">
                            <p><i class="fa fa-bar-chart" aria-hidden="true"></i>
                                <strong>Total cuenta</strong>
                            </p>
                        </div>
                        <div class="card-content">
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
                            <canvas
                                class="chart chart-line"
                                chart-data="datos"
                                chart-labels="etiquetas"
                                chart-series="series"></canvas>
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
                            <canvas class="chart chart-doughnut" chart-data="dataPie" chart-labels="etiquetasPie"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fin Gráficos -->
            <!-- Inicio listados -->
            <div class="row">
                <div class="col-lg-7 col-md-12">
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
                                    Persona
                                </th>
                                <th>
                                    Tipo
                                </th>
                                <th>
                                    Cuantía
                                </th>
                                <th>
                                    Fecha
                                </th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Alejandro</td>
                                        <td>Ingreso</td>
                                        <td>1.200,45</td>
                                        <td>10/09/2020</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <p><i class="fa fa-bell" aria-hidden="true"></i>
                                <strong>Notificaciones</strong>
                            </p>
                        </div>
                        <div class="card-content">
                            <table class="table" style="text-align: left">
                                <thead>
                                <th>Mensaje</th>
                                <th>Fecha</th>
                                <th>Leído</th>
                                </thead>
                                <tbody>
                                    <tr class="ver" onclick="javascript:alert('ver')">
                                        <td>Alejandro...</td>
                                        <td>01/09/2020</td>
                                        <td><i class="fa fa-times read" aria-hidden="true"></i>
                                        </td>
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
