@extends('tratamiento-mgmt.base')
@if (1 == Auth::user()->rol_id || 2 == Auth::user()->rol_id)
@section('action-content')
    <!-- Main content -->
    <section class="content">
      <div class="box">
  <div class="box-header">
    <div class="row">
        <div class="col-sm-8">
          <h3 class="box-title">Mostrar Tratamiento</h3>
        </div>
        @if (1 == Auth::user()->rol_id || 2 == Auth::user()->rol_id)
        <div class="col-sm-4">
          <a class="btn btn-primary" href="{{ route('tratamiento-management.create') }}"><i class="glyphicon glyphicon-plus-sign"></i> Nuevo Tratamiento</a>
        </div>
        @endif
    </div>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
      <div class="row">
        <div class="col-sm-6"></div>
        <div class="col-sm-6"></div>
      </div>
      {{ csrf_field() }}
      <div>
        <h4>
          <label style="color:red">{{ $message }}</label>
        </h4>
      </div>
      <form method="POST" action="{{ route('tratamiento-management.search') }}">
         {{ csrf_field() }}
         @component('layouts.search', ['title' => 'Buscar'])
         <table id="example2">
            <tr>
            <td class="col-md-6">
              <input id="nombre1" type="text" class="form-control" placeholder="buscar por Paciente/Terapia/Médico" name="nombre1" value="{{ old('nombre1') }}"  onKeyUp="this.value=this.value.toUpperCase();" >
            </td>
            <td class="col-md-3">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" value="{{ old('fecha_inicio') }}" placeholder="Inicio" name="fecha_inicio" class="form-control pull-right" id="from">
                </div>
            </td>
            <td class="col-md-3">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" value="{{ old('fecha_fin') }}" placeholder="Fin" name="fecha_fin" class="form-control pull-right" id="to">
                </div>
            </td>
            </tr>
          </table>
        @endcomponent
      </form>
    <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
      <div class="row">
        <div class="col-sm-12">
          <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
            <thead>
              <tr role="row">
                <th width="30%" class="sorting" tabindex="0" aria-controls="ffff" rowspan="1" colspan="1" aria-label="rol: activate to sort column ascending">Paciente</th>
                <th width="10%" class="sorting" tabindex="0" aria-controls="ffff" rowspan="1" colspan="1" aria-label="rol: activate to sort column ascending">Terapia</th>
                <th width="30%" class="sorting hidden-xs" tabindex="0" aria-controls="ffff" rowspan="1" colspan="1" aria-label="rol: activate to sort column ascending">Medico</th>
                <th width="5%" class="sorting hidden-xs" tabindex="0" aria-controls="ffff" rowspan="1" colspan="1" aria-label="rol: activate to sort column ascending">Citas Asignadas</th>
                <th width="5%" class="sorting hidden-xs" tabindex="0" aria-controls="ffff" rowspan="1" colspan="1" aria-label="rol: activate to sort column ascending">Citas sin Asignar</th>
                <th width="7%" class="sorting hidden-xs" tabindex="0" aria-controls="ffff" rowspan="1" colspan="1" aria-label="rol: activate to sort column ascending">Fecha</th>
                <th width="15%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Action: activate to sort column ascending">Opciones</th>
              </tr>
            </thead>
            <tbody>
            @foreach ($tratamientos as $tratamiento)
                <tr role="row" class="odd">
                  <td class="sorting_1">{{ $tratamiento->primer_nombre }} {{ $tratamiento->segundo_nombre }} {{ $tratamiento->tercer_nombre }} {{ $tratamiento->primer_apellido }} {{ $tratamiento->segundo_apellido }} {{ $tratamiento->tercer_apellido }}</td>
                  <td class="sorting_1"><label style="color:{{ $tratamiento->color }}">{{ $tratamiento->nombre_terapia }}</label></td>
                  <td class="hidden-xs">{{ $tratamiento->nombre_medico }}</td>
                  <td class="hidden-xs">{{ $tratamiento->asignados }}</td>
                  <td class="hidden-xs">{{ $tratamiento->restantes }}</td>
                  <td class="hidden-xs">{{ $tratamiento->fecha }}</td>
                  <td>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" onKeyUp="this.value=this.value.toUpperCase();">
                        @if (1 == Auth::user()->rol_id || 2 == Auth::user()->rol_id)
                        <a href="{{ route('tratamiento-management.edit', ['id' => $tratamiento->id]) }}" class="btn btn-warning col-sm-3 col-xs-2 btn-margin"><i class="fa fa-edit"></i>
                        </a>
                        @endif
                    </form>
                  </td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-5">
          <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">Registros mostrados {{count($tratamientos)}}, registros existentes {{count($tratamientos)}}</div>
        </div>
        <div class="col-sm-7">
          <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
            {{ $tratamientos->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /.box-body -->
</div>
    </section>
    <!-- /.content -->
  </div>
@endsection
@endif
