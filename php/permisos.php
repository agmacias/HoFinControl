<div ng-controller="PermisosController" ng-init="init()">
    <div class="content">
        <div class="container-fluid">
            <form>
                <div class="login-form" ng-if="permisosView.pantalla==0">
                    <!-- inicio tarjetas -->
                    <div class="card card-margin">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class ="cabeceraLabel" for="usuario"><u>U</u>suario:</label>
                                <input type="text" class="form-control" id="usuario" ng-model="permisosView.usuario" placeholder="Usuario" accesskey="U" tabindex="1" maxlength="20"/>
                            </div>
                            <div class="form-group col-md-4">
                                <label class ="cabeceraLabel" for="nombre"><u>N</u>ombre:</label>
                                <input type="text" class="form-control" id="nombre" ng-model="permisosView.nombre" placeholder="Nombre" accesskey="C" tabindex="2" maxlength="45"/>
                            </div>
                            <div class="form-group col-md-4">
                                <label class ="cabeceraLabel" for="apellido1"><u>P</u>rimer <u>a</u>pellido:</label>
                                <input type="text" class="form-control" id="apellido1" ng-model="permisosView.apellido1" placeholder="Primer apellido" accesskey="A" tabindex="3" maxlength="45"/>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class ="cabeceraLabel" for="apellido2"><u>S</u>egundo apellido:</label>
                                <input type="text" class="form-control" id="apellido2" ng-model="permisosView.apellido2" placeholder="Segundo apellido" accesskey="S" tabindex="4" maxlength="45"/>
                            </div>
                            <div class="form-group col-md-4">
                                <label class ="cabeceraLabel" for="fechaDesde">Fecha nacimiento <u>d</u>esde:</label>
                                <input type="date" class="form-control" id="fechaDesde" ng-model="permisosView.fechaDesde" placeholder="Fecha desde" accesskey="D" tabindex="5" maxlength="12"/>
                            </div>
                            <div class="form-group col-md-4">
                                <label class ="cabeceraLabel" for="fechaHasta">Fecha nacimiento <u>h</u>asta:</label>
                                <input type="date" class="form-control" id="fechaHasta" ng-model="permisosView.fechaHasta" placeholder="Fecha hasta" accesskey="H" tabindex="6" maxlength="12"/>
                            </div>
                        </div>
                 </div>
                    <div class="form-row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-sm" id="buscar" name="buscar" tabindex="7" ng-click="searchUsuarios()">Buscar</button>                            
                        </div>
                    </div>
                </div>
                <div class="row" ng-if="permisosView.pantalla==0">
                    <div class="col-lg-12 col-md-12">
                        <div class="card card-margin">
                            <table class="table table-striped" style="text-align: left">
                                <thead>
                                    <tr>
                                        <th scope="col">Usuario</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Primer apellido</th>
                                        <th scope="col">Segundo apellido</th>
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
                                    <tr ng-if="data.usuarios.length>0" ng-repeat="option in data.usuarios | filter : paginate">
                                        <td>{{option.usuario}}</td>
                                        <td>{{option.nombre}}</td>
                                        <td>{{option.apellido1}}</td>
                                        <td>{{option.apellido2}}</td>
                                        <td ><i title="Gestionar permisos" class="fa fa-pencil" ng-click="getPermisos(option)" aria-hidden="true"></i></td>
                                    </tr>
                                    <tr ng-if="data.usuarios.length==0">
                                        <td>No hay usuarios registrados</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="login-form" ng-if="permisosView.pantalla==1">
                    <!-- inicio tarjetas -->
                    
                    <div class="card card-margin">
                        <div class="form-row">
                            <div class="col-2">Permisos para: </div>
                            <div class="col-10 textNormal">{{permisosView.userSelect}}</div>
                        </div>
                        <div class="form-row">                            
                            <div class="form-group col-md-3">
                                <label>Permisos disponibles</label>
                                <select name="permisosOrigen" id="permisosOrigen" ng-model="permisosView.permisosOrigen" class="form-control" multiple>
                                    <option ng-repeat="option in data.permisosOrigen" value="{{option}}">{{option.nombre}}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2"></div>
                            <div class="form-group col-md-2">
                                <div style="margin-top: 30px;text-align: center;"></div>
                                <div class="form-row">
                                    <i title="Asignar" class="fa fa-angle-double-right" ng-click="asignTo()" aria-hidden="true"></i>
                                </div>
                                <div class="form-row">
                                    <i title="Desasignar" class="fa fa-angle-double-left" ng-click="unAsignTo()" aria-hidden="true"></i>
                                </div>
                            </div>                            
                            <div class="form-group col-md-3">
                                <label>Permisos asociados</label>
                                <select class="form-control" ng-model="permisosView.permisosDestino" multiple>
                                  <option ng-repeat="option in data.permisosDestino" value="{{option}}">{{option.nombre}}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-1"></div>
                        </div>                        
                    </div>
                    <div class="form-row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-sm" id="aceptar" name="aceptar" tabindex="5" ng-click="savePermisos()">Aceptar</button>
                            <button type="submit" class="btn btn-primary btn-sm" id="volver" name="volver" tabindex="5" ng-click="permisosView.pantalla=0;$scope.operacion=1;getUsuarios()">Volver</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
