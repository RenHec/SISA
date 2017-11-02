@extends('diasemanausuario-mgmt.base')
@if (1 == Auth::user()->rol_id || 2 == Auth::user()->rol_id)
@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-md-47 col-md-offset-0">
            <div class="panel panel-default">
                <div class="panel-heading">Nuevo Empleado</div>
                <div class="panel-body">

            <input type="hidden" name="_method" value="PATCH">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

        @component('layouts.esconder_info', ['title' => 'Nombre y Apellido'])
        <table id="example2" class="table table-responsive">
            <tr>
            <td>
            <div class="form-group{{ $errors->has('dpi') ? ' has-error' : '' }}">
                <label for="dpi" class="col-md-6 control-label"><label style="color:red">*</label> DPI</label>
                    <div class="col-md-5">
                        <input id="dpi" type="text" class="form-control" placeholder="0000000000000" name="dpi" value="{{ $user->dpi }}" required disabled>
                    </div>
            </div>
            </td>
            </tr>
            <tr>
            <td>
            <div class="form-group">
                <label for="nombre1" class="col-md-6 control-label"><label style="color:red">*</label> Primer Nombre</label>
                    <div class="col-md-5">
                        <input id="nombre1" type="text" class="form-control" placeholder="primer nombre" name="nombre1" value="{{ $user->nombre1 }}" onKeyUp="this.value=this.value.toUpperCase();" required autofocus disabled>
                    </div>
            </div>
            </td>
            <td>
            <div class="form-group">
                <label for="nombre2" class="col-md-6 control-label">Segundo Nombre</label>

                    <div class="col-md-5">
                        <input id="nombre2" type="text" class="form-control" placeholder="segundo nombre" name="nombre2" value="{{ $user->nombre2 }}" disabled>
                    </div>
            </div>
            </td>
            <td>
            <div class="form-group">
                <label for="nombre3" class="col-md-6 control-label">Tercer Nombre</label>

                    <div class="col-md-5">
                        <input id="nombre3" type="text" class="form-control" placeholder="tercer nombre" name="nombre3" value="{{ $user->nombre3 }}" disabled>
                    </div>
            </div>
            </td>
            </tr>
            <tr>
            <td>
            <div class="form-group">
                <label for="apellido1" class="col-md-6 control-label"><label style="color:red">*</label> Primer Apellido</label>
                    <div class="col-md-5">
                        <input id="apellido1" type="text" class="form-control" placeholder="primer apellido" name="apellido1" value="{{ $user->apellido1 }}" required autofocus disabled>
                    </div>
            </div>
            </td>
            <td>
            <div class="form-group">
                <label for="apellido2" class="col-md-6 control-label">Segundo Apellido</label>

                    <div class="col-md-5">
                        <input id="apellido2" type="text" class="form-control" placeholder="segundo apellido" name="apellido2" value="{{ $user->apellido2 }}" autofocus disabled>
                    </div>
            </div>
            </td>
            <td>
            <div class="form-group">
                <label for="apellido3" class="col-md-6 control-label">Tercer Apellido</label>

                    <div class="col-md-5">
                        <input id="apellido3" type="text" class="form-control" placeholder="tercer apellido" name="apellido3" value="{{ $user->apellido3 }}" autofocus disabled>
                    </div>
            </div>
            </td>
            </tr>
        </div>
        </table>
        @endcomponent

        @component('layouts.esconder_info', ['title' => 'Datos de la Cuenta'])
        <table id="example2" class="table table-responsive">
            <tr>
            <td>
            <div class="form-group">
                <label for="username" class="col-md-4 control-label"><label style="color:red">*</label> Usuario</label>

                    <div class="col-md-6">
                        <input id="username" type="text" class="form-control" placeholder="usuario" name="username" value="{{ $user->username }}" required autofocus disabled>
                    </div>
            </div>
            </td>
            <td>
            <div class="form-group">
                <label for="email" class="col-md-4 control-label">Correo Electrónico</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control" name="email" value="{{ $user->email }}" autofocus disabled>
                    </div>
            </div>
            </td>
            </tr>
            <tr>
            <td>
            <div class="form-group">
                <label for="password" class="col-md-4 control-label"><label style="color:red">*</label> Nueva Contraseña</label>

                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control" name="password" required autofocus disabled>
                    </div>
            </div>
            </td>
            <td>
            <div class="form-group">
                <label for="password-confirm" class="col-md-4 control-label"><label style="color:red">*</label> Confirmar Contraseña</label>

                    <div class="col-md-6">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autofocus disabled>
                    </div>
            </div>
            </td>
            </tr>
        </table>
        @endcomponent

        @component('layouts.esconder_info', ['title' => 'Dirección'])
        <table id="example2" class="table table-responsive">
            <tr>
            <td>
            <div class="form-group">
                <label class="col-md-5 control-label"><label style="color:red">*</label> Departamento</label>
                    <div class="col-md-7">
                        <select class="form-control" name="departamento_id" required autofocus disabled>
                                <option>{{$user->Departamento}}</option>
                        </select>
                    </div>
            </div>
            </td>
            <td>
            <div class="form-group">
                <label class="col-md-4 control-label"><label style="color:red">*</label> Municipio</label>
                    <div class="col-md-7">
                        <select class="form-control" name="municipio_id" required autofocus disabled>
                                <option>{{$user->Municipio}}</option>
                        </select>
                    </div>
            </div>
            </td>
            <td>
            <div class="form-group">
                <label for="direccion" class="col-md-3 control-label">Dirección</label>
                    <div class="col-md-9">
                        <input id="direccion" type="direccion" class="form-control" placeholder="colonia/barrio" name="direccion" value="{{ $user->direccion }}" autofocus disabled>
                    </div>
            </div>
            </td>
            </tr>
        </table>
        @endcomponent

        @component('layouts.esconder_info', ['title' => 'Datos Personales'])
        <table id="example2" class="table table-responsive">
            <tr>
            <td>
            <div class="form-group">
                <label class="col-md-5 control-label"><label style="color:red">*</label> Fecha de Nacimiento</label>
                    <div class="col-md-5">
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" value="{{ $user->fecha_nacimiento }}" placeholder="30/01/1990" name="fecha_nacimiento" class="form-control pull-right" id="fechaNacimiento" style='display:none;'>
                            <input type="text" value="{{ $user->fecha_nacimiento }}" placeholder="30/01/1990" name="nacimiento" class="form-control pull-right" id="nacimiento" required disabled>
                        </div>
                    </div>
            </div>
            </td>
            <td>
                <label for="edad" class="col-md-3 control-label">Edad</label>
                    <div class="col-md-3">
                        <input id="edad" type="edad" class="form-control" name="edad" disabled>
                        <script type="text/javascript">
                            var fechaNac = document.getElementById("fechaNacimiento").value;
                            var fechaHoy = new Date();
                            var anioNac = parseInt(fechaNac.substring(fechaNac.lastIndexOf('/')+1));
                            var mesNac = parseInt(fechaNac.substr(fechaNac.indexOf('/')+1,fechaNac.lastIndexOf('/')+1));
                            var diaNac = parseInt(fechaNac.substr(0,fechaNac.lastIndexOf('/')+1));
                            var edad = parseInt(fechaHoy.getFullYear())-anioNac;
                                if(edad)
                                    if(mesNac<=parseInt(fechaHoy.getMonth()+1))
                                        document.getElementById("edad").value=edad;
                                    else
                                        document.getElementById("edad").value=edad-1;
                        </script>
                    </div>
            </td>
            <td>
            <div class="form-group">
                <label for="telefono" class="col-md-4 control-label">Teléfono</label>

                    <div class="col-md-6">
                        <input id="telefono" type="telefono" class="form-control" placeholder="00000000" name="telefono" value="{{ $user->telefono }}" autofocus disabled>
                    </div>
            </div>
            </td>
            </tr>
            <tr>
            <td>
              <div class="form-group">
                  <label class="col-md-4 control-label"><label style="color:red">*</label> Género</label>
                      <div class="col-md-6">
                          <select class="form-control" name="genero_id" required autofocus disabled>
                                <option>{{$user->Genero}}</option>
                          </select>
                      </div>
              </div>
            </td>
            </tr>
        </table>
        @endcomponent

        @component('layouts.esconder_info', ['title' => 'Datos Laborales'])
        <table id="example2" class="table table-responsive">
            <tr>
            <td>
            <div class="form-group">
                <label class="col-md-4 control-label"><label style="color:red">*</label> Fecha de Ingreso</label>
                    <div class="col-md-5">
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" value="{{ $user->fecha_ingreso }}" placeholder="30/01/1990" name="fecha_ingreso" class="form-control pull-right" id="fechaIngreso" required disabled>
                        </div>
                    </div>
            </div>
            </td>
            <td>
            <div class="form-group">
                <label class="col-md-4 control-label"><label style="color:red">*</label> Puesto Encargado</label>
                    <div class="col-md-6">
                        <select class="form-control" name="rol_id" required autofocus disabled>
                              <option>{{$user->Rol}}</option>
                        </select>
                    </div>
            </div>
            </td>
            </tr>
        </table>
        @endcomponent

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-47 col-md-offset-0">
            <div class="panel panel-default">
                @component('layouts.esconder_info', ['title' => 'Dias a Laborar'])
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('diasemanausuario-management.store') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                          <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                            <div class="row">
                                <div class="col-sm-10">
                                  <table id="example2" class="table table-responsive" role="grid" aria-describedby="example2_info">
                                      <tr>
                                      <td role="row">
                                        <label>
                                            <input type="checkbox" name="check" id="check" value="1" onchange="javascript:showContent()" checked>
                                            NINGUNO
                                        </label>
                                      </td>
                                      </tr>
                                  </table>
                                </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group" id="editar_direccion" style="display: none;">
                            <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                                <div class="row">
                                    <div class="col-sm-10">
                                        <table id="example2" class="table table-responsive" role="grid" aria-describedby="example2_info">
                                            <tr>
                                            @foreach ($diasemanas as $diasemana)
                            <td role="row" class="col-md-1"><input type="checkbox" id="inlineCheckbox1" name="diasemana[]" value="{{$diasemana->id}}">  {{ $diasemana->nombre }}</td>
                                            @endforeach
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                @endcomponent
                <table id="example2" class="table table-responsive">
                <tr>
                <td>
                @if (1 == Auth::user()->rol_id || 2 == Auth::user()->rol_id)
                    <div class="col-md-4 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            Siguiente <i class="fa fa-chevron-right"></i>
                        </button>

                        <a href="{{ route('user-management.show', ['id' => $user->id]) }}" class="btn btn-danger col-sm-3 col-xs-6 btn-margin"><i class="fa fa-user-times"></i> Cancelar
                        </a>
                    </div>
                @endif
                </tr>
                </tr>
                </table>
            </form>
            </div>
        </div>
    </div>
</div>
@endsection
@endif
