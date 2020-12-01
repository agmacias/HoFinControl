<div ng-controller="MantIngresosController" ng-init="init()">
    <div class="content">
        <div class="container-fluid">
            <form>
                <div class="login-form" ng-if="mantIngresosView.pantalla==0">
                    <!-- inicio tarjetas -->
                    <div class="card card-margin">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class ="cabeceraLabel" for="tipo"><u>C</u>uenta:</label>
                                <select name="tipo" id="tipo" ng-model="mantIngresosView.cuenta" class="form-control" accesskey="C" tabindex="1">
                                    <option value="0" selected="selected">Seleccione una cuenta</option>
                                    <option ng-repeat="option in data.cuentas" value="{{option.id}}">{{option.nombre}}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label class ="cabeceraLabel" for="tipoIngreso"><u>T</u>ipo ingreso:</label>
                                <select name="tipoIngreso" id="tipoIngreso" ng-model="mantIngresosView.tipoIngreso" class="form-control" accesskey="T" tabindex="2">
                                    <option value="0" selected="selected">Seleccione un tipo</option>
                                    <option ng-repeat="option in data.tipo_ingreso" value="{{option.id}}">{{option.nombre}}</option>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label class ="cabeceraLabel" for="fecha">Fecha <u>D</u>esde:</label>
                                <input type="date" class="form-control" id="fecha" ng-model="mantIngresosView.fechaDesde" placeholder="Fecha desde" accesskey="D" tabindex="3" maxlength="12"/>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class ="cabeceraLabel" for="fecha">Fecha <u>H</u>asta:</label>
                                <input type="date" class="form-control" id="fecha" ng-model="mantIngresosView.fechaHasta" placeholder="Fecha hasta" accesskey="H" tabindex="4" maxlength="12"/>
                            </div>
                            <div class="form-group col-md-8">
                                <label class ="cabeceraLabel" for="asunto"><u>A</u>sunto:</label>
                                <input type="text" class="form-control" id="asunto" ng-model="mantIngresosView.asunto"  accesskey="A" tabindex="4" maxlength="50" placeholder='Asunto'/>
                            </div>
                        </div>                        
                    </div>
                    <div class="form-row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-sm" id="buscar" name="buscar" tabindex="7" ng-click="searchIngresoForm()">Buscar</button>
                            <button type="submit" class="btn btn-primary btn-sm" id="nuevo" name="nuevo" tabindex="6" ng-click="changeToNew()">Nuevo</button>
                        </div>
                    </div>
                </div>
                
                <div class="row" ng-if="mantIngresosView.pantalla==0">
                    <div class="col-lg-12 col-md-12">
                        <div class="card card-margin">
                            <table class="table table-striped" style="text-align: left">
                                <thead>
                                    <tr>
                                        <th scope="col">Cuenta</th>
                                        <th scope="col">Tipo Ingreso</th>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Asunto</th>
                                        <th scope="col">Cuantia</th>
                                        <th scope="col">Acción</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                <td colspan="6">
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
                                    <tr ng-if="data.ingresos.length>0" ng-repeat="option in data.ingresos | filter : paginate">
                                        <td>{{option.nombre_cuenta}}</td>
                                        <td>{{option.tipo_nombre}}</td>
                                        <td>{{option.fecha | date:"MM/dd/yyyy"}}</td>
                                        <td>{{option.asunto}}</td>
                                        <td>{{option.cuantia}}</td>
                                        <td ><i title="Eliminar" class="fa fa-times read" ng-click="eliminarIngreso(option)" aria-hidden="true" />  <i title="Modificar" class="fa fa-pencil" ng-click="modificarIngreso(option)" aria-hidden="true"></i></td>
                                    </tr>
                                    <tr ng-if="data.ingresos.length==0">
                                        <td colspan="6">No hay ingresos registrados</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="login-form" ng-if="mantIngresosView.pantalla==1">
                    <!-- inicio tarjetas -->
                    <div class="card card-margin">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class ="cabeceraLabel" for="tipo"><u>C</u>uenta (obligatorio):</label>
                                <select name="tipo" id="tipo" ng-model="mantIngresosView.cuenta" class="form-control" accesskey="C" tabindex="1" required>
                                    <option value="" selected="selected">Seleccione una cuenta</option>
                                    <option ng-repeat="option in data.cuentas" value="{{option.id}}">{{option.nombre}}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label class ="cabeceraLabel" for="tipoIngreso"><u>T</u>ipo ingreso (obligatorio):</label>
                                <select name="tipoIngreso" id="tipoIngreso" ng-model="mantIngresosView.tipoIngreso" class="form-control" accesskey="T" tabindex="2" required>
                                    <option value="" selected="selected">Seleccione un tipo</option>
                                    <option ng-repeat="option in data.tipo_ingreso" value="{{option.id}}">{{option.nombre}}</option>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label class ="cabeceraLabel" for="fecha">Fecha <u>i</u>nicio (obligatorio):</label>
                                <input type="date" class="form-control" id="fecha" ng-model="mantIngresosView.fecha" placeholder="Fecha" accesskey="I" tabindex="3" maxlength="12" required/>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class ="cabeceraLabel" for="cuantia">C<u>u</u>antía: (Obligatoria)</label>
                                <input type="number" class="form-control" id="cuantia" ng-model="mantIngresosView.cuantia" placeholder="Cuantía" accesskey="C" tabindex="3" maxlength="14" step='0.01' value='0.00' placeholder='0.00' required/>
                            </div>
                            <div class="form-group col-md-8">
                                <label class ="cabeceraLabel" for="asunto"><u>A</u>sunto: (Obligatorio)</label>
                                <input type="text" class="form-control" id="asunto" ng-model="mantIngresosView.asunto"  accesskey="A" tabindex="4" maxlength="50" placeholder='Asunto' required/>
                            </div>                            
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label class ="cabeceraLabel" for="descripcion"><u>D</u>escripción:</label>
                                <textarea class="form-control" id="descripcion" ng-model="mantIngresosView.descripcion" maxlength="500" accesskey="D" rows="3" tabindex="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-12">                            
                            <button type="submit" class="btn btn-primary btn-sm" id="acepar" name="aceptar" tabindex="7" ng-click="sendIngresoForm()">Aceptar</button>
                            <button type="submit" class="btn btn-primary btn-sm" id="nuevo" name="nuevo" tabindex="6" ng-click="mantIngresosView.pantalla=0;operacion=1">Volver</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
