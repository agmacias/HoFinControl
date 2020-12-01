<div ng-controller="CuentasController" ng-init="init()">
    <div class="content">
        <div class="container-fluid">
            <form>
                <div class="login-form" ng-if="cuentasView.pantalla==0">
                    <!-- inicio tarjetas -->
                    <div class="card card-margin">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class ="cabeceraLabel" for="nombre"><u>N</u>ombre:</label>
                                <input type="text" class="form-control" id="nombre" ng-model="cuentasView.nombre" placeholder="Nombre" accesskey="N" tabindex="1" maxlength="45"/>
                            </div>
                            <div class="form-group col-md-4">
                                <label class ="cabeceraLabel" for="cuantia"><u>C</u>uantía desde:</label>
                                <input type="number" class="form-control" id="cuantia" ng-model="cuentasView.cuantiaDesde" placeholder="Cuantía desde" accesskey="C" tabindex="3" maxlength="14" step='0.01' value='0.00' placeholder='0.00'/>
                            </div>
                            <div class="form-group col-md-4">
                                <label class ="cabeceraLabel" for="cuantia">C<u>u</u>antía hasta:</label>
                                <input type="number" class="form-control" id="cuantia" ng-model="cuentasView.cuantiaHasta" placeholder="Cuantía hasta" accesskey="U" tabindex="3" maxlength="14" step='0.01' value='0.00' placeholder='0.00'/>
                            </div>                            
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class ="cabeceraLabel" for="fecha">Fecha <u>d</u>esde:</label>
                                <input type="date" class="form-control" id="fecha" ng-model="cuentasView.fechaDesde" placeholder="Fecha desde" accesskey="D" tabindex="4" maxlength="12"/>
                            </div>
                            <div class="form-group col-md-4">
                                <label class ="cabeceraLabel" for="fechafin">Fecha <u>h</u>asta:</label>
                                <input type="date" class="form-control" id="fechafin" ng-model="cuentasView.fechaHasta" placeholder="Fecha hasta" accesskey="H" tabindex="4" maxlength="12"/>
                            </div>
                        </div>
                 </div>
                    <div class="form-row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-sm" id="buscar" name="buscar" tabindex="5" ng-click="searchCuentas()">Buscar</button>
                            <button type="submit" class="btn btn-primary btn-sm" id="nuevo" name="nuevo" tabindex="5" ng-click="changeToNew()">Nuevo</button>                            
                        </div>
                    </div>
                </div>
                <div class="row" ng-if="cuentasView.pantalla==0">
                    <div class="col-lg-12 col-md-12">
                        <div class="card card-margin">                            
                            <table class="table table-striped" style="text-align: left">
                                <thead>
                                    <tr>                                        
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Cuantía</th>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Principal</th>
                                        <th scope="col">Acción</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                <td colspan="5">
                                    <ul class="pagination pull-right">
                                        <li ng-class="page-item">
                                            <span class="page-link color-page-link" ng-click="currentPage > 1 && prevPage()">« Prev</span>
                                        </li>
                                        <li ng-repeat="n in ret"
                                            ng-class="page-item"
                                            ng-click="setPage(n + 1)">
                                            <span class="page-link" ng-bind="n + 1">1</span>
                                        </li>
                                        <li ng-class="page-item">
                                            <span class="page-link" ng-click="currentPage < ret.length && nextPage()">Next »</span>
                                        </li>
                                    </ul>
                                </td>
                                </tfoot>
                                <tbody>
                                    <tr ng-repeat="option in data.cuentas | filter : paginate">                                        
                                        <td>{{option.nombre}}</td>
                                        <td>{{option.cuantia}}</td>
                                        <td>{{option.fecha}}</td>
                                        <td>
                                            <input type="radio" name="defecto" value="{{option.id}}" ng-model="cuentasView.defecto" ng-click="marcarDefecto(option)"/>
                                        </td>
                                        <td ><i title="Eliminar" class="fa fa-times read" ng-click="eliminarCuenta(option)" aria-hidden="true" />  <i title="Modificar" class="fa fa-pencil" ng-click="modificarCuenta(option)" aria-hidden="true"></i></td>
                                    </tr>
                                </tbody>
                            </table>                            
                        </div>
                    </div>
                </div>
                <div class="login-form" ng-if="cuentasView.pantalla==1">
                    <!-- inicio tarjetas -->
                    <div class="card card-margin">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class ="cabeceraLabel" for="nombre"><u>N</u>ombre (obligatorio):</label>
                                <input type="text" class="form-control" id="nombre" ng-model="cuentasView.nombre" placeholder="Nombre" accesskey="N" tabindex="1" maxlength="45" required />
                            </div>
                            <div class="form-group col-md-4">
                                <label class ="cabeceraLabel" for="cuantia"><u>C</u>uantía:</label>
                                <input type="number" class="form-control" id="cuantia" ng-model="cuentasView.cuantia" placeholder="Cuantía" accesskey="C" tabindex="3" maxlength="14" step='0.01' value='0.00' placeholder='0.00'/>
                            </div>
                            <div class="form-group col-md-4">
                                <label class ="cabeceraLabel" for="fecha">Fecha <u>a</u>lta (obligatorio):</label>
                                <input type="date" class="form-control" id="fecha" ng-model="cuentasView.fecha" placeholder="Fecha" accesskey="A" tabindex="4" maxlength="12" required/>
                            </div>
                        </div>
                        <div class="form-row">                            
                            <div class="form-group col-md-4">
                                <label class ="cabeceraLabel" for="fechafin">Fecha <u>b</u>aja:</label>
                                <input type="date" class="form-control" id="fechafin" ng-model="cuentasView.fechaFin" placeholder="Fecha" accesskey="B" tabindex="4" maxlength="12"/>
                            </div>
                        </div>                        
                    </div>
                    <div class="form-row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-sm" id="aceptar" name="aceptar" tabindex="5" ng-click="sendCuentasForm()">Aceptar</button>
                            <button type="submit" class="btn btn-primary btn-sm" id="volver" name="volver" tabindex="5" ng-click="cuentasView.pantalla=0;$scope.operacion=1">Volver</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
