<div ng-controller="ListIngresosController" ng-init="init()">
    <div class="content">
        <div class="container-fluid">
            <form>
                <div class="login-form">
                    <!-- inicio tarjetas -->
                    <div class="card card-margin">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class ="cabeceraLabel" for="cuentas"><u>C</u>uenta:</label>
                                <select name="cuentas" id="cuentas" ng-model="listIngresosView.cuenta" class="form-control" accesskey="C" tabindex="1" multiple>
                                    <option ng-repeat="option in data.cuentas" value="{{option.id}}">{{option.nombre}}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label class ="cabeceraLabel" for="tipoIngreso"><u>T</u>ipo ingreso:</label>
                                <select name="cuentas" id="cuentas" ng-model="listIngresosView.tipoIngreso" class="form-control" accesskey="T" tabindex="2" multiple>
                                    <option ng-repeat="option in data.tipo_ingreso" value="{{option.id}}">{{option.nombre}}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <span class ="cabeceraLabel"><u>A</u>grupar por:</span>
                                <div class="input-group">
                                        <div class="col-md-3 custom-control custom-radio custom-control-inline">
                                          <input class="custom-control-input" type="radio" ng-model="listIngresosView.agrupar" name="agrupar" id="cuenta" value="cuenta" accesskey="A" tabindex="6"/>
                                          <label class="custom-control-label pt-1" for="cuenta">
                                                Cuenta
                                          </label>
                                        </div>
                                        <div class="col-md-7 custom-control custom-radio custom-control-inline">
                                          <input class="custom-control-input" type="radio" ng-model="listIngresosView.agrupar" name="agrupar" id="gasto" value="gasto" tabindex="7" accesskey="6" />
                                          <label class="custom-control-label pt-1" for="gasto">
                                                Tipo de ingreso
                                          </label>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class ="cabeceraLabel" for="fecha">Fecha <u>D</u>esde:</label>
                                <input type="date" class="form-control" id="fecha" ng-model="listIngresosView.fechaDesde" placeholder="Fecha desde" accesskey="D" tabindex="3" maxlength="12"/>
                            </div>
                            <div class="form-group col-md-4">
                                <label class ="cabeceraLabel" for="fecha">Fecha <u>H</u>asta:</label>
                                <input type="date" class="form-control" id="fecha" ng-model="listIngresosView.fechaHasta" placeholder="Fecha hasta" accesskey="H" tabindex="4" maxlength="12"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-sm" id="buscar" name="buscar" tabindex="7" ng-click="findResults()">Buscar</button>
                            <button type="submit" class="btn btn-primary btn-sm" id="export" name="nuevo" tabindex="6" ng-click="exportar()">Exportar</button>
                        </div>
                    </div>
                </div>

                <div class="row" ng-if="listIngresosView.busqueda==1" id="table2excel">
                    <div ng-repeat="option in data.gastos" class="col-lg-12 col-md-12">
                        <div ng-if="option.groupBy!=null" class="col-lg-12 col-md-12">
                            <div class="card card-head-list"><span>{{option.groupBy}}</span></div>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <div class="card">
                                <table class="table table-striped" style="text-align: left">
                                    <thead>
                                        <tr>
                                            <th scope="col">Cuenta</th>
                                            <th scope="col">Tipo Ingreso</th>
                                            <th scope="col">Fecha</th>
                                            <th scope="col">Asunto</th>
                                            <th scope="col">Cuantia</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-if="option.datos.length>0" ng-repeat="datos in option.datos">
                                            <td>{{datos.nombre_cuenta}}</td>
                                            <td>{{datos.tipo_nombre}}</td>
                                            <td>{{datos.fecha | date:"MM/dd/yyyy"}}</td>
                                            <td>{{datos.asunto}}</td>
                                            <td>{{datos.cuantia}}</td>
                                        </tr>
                                        <tr ng-if="option.datos.length==0">
                                            <td colspan="5">No hay inmgresos registrados</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
