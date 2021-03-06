<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use App\Tratamiento;
use App\Bitacora;
use App\Paciente;
use App\Medico;
use App\Terapia;
use Auth;
//use DateTime;

class TratamientoController extends Controller {

    protected $redirectTo = '/sisa/tratamiento-management'; //redirecciona la ruta

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $tratamientos = DB::table('tratamientos')
        ->leftJoin('pacientes', 'tratamientos.paciente_id', '=', 'pacientes.id')
        ->leftJoin('medicos', 'tratamientos.medico_id', '=', 'medicos.id')
        ->leftJoin('terapias', 'tratamientos.terapia_id', '=', 'terapias.id')
        ->select('tratamientos.*',  'pacientes.nombre1 as primer_nombre',
                        'pacientes.nombre2 as segundo_nombre',
                        'pacientes.nombre3 as tercer_nombre',
                        'pacientes.apellido1 as primer_apellido',
                        'pacientes.apellido2 as segundo_apellido',
                        'pacientes.apellido3 as tercer_apellido',
                        'medicos.nombre as nombre_medico',
                        'terapias.nombre as nombre_terapia',
                        'terapias.color as color')
        ->orderBy('fecha', 'desc')->paginate(10);
        $message = ' ';
        return view('tratamiento-mgmt/index', ['tratamientos' => $tratamientos, 'message' => $message]);
    }

    public function create() {
        $pacientes = Paciente::select('pacientes.*')->orderBy('nombre1','asc')->get();
        $medicos = Medico::select('medicos.*')->orderBy('nombre','asc')->get();
        $terapias = Terapia::select('terapias.*')->where('id', '!=', 1)->orderBy('nombre','asc')->get();

        return view('tratamiento-mgmt/create', ['pacientes' => $pacientes, 'medicos' => $medicos, 'terapias' => $terapias]);
    }

    public function store(Request $request) {
        date_default_timezone_set('america/guatemala');
        $format = 'd/m/Y';
        $now = date($format);
        $this->validateInsertTratamiento($request);
        $tratamiento = new Tratamiento();
        $tratamiento->fecha = $now;
        $tratamiento->descripcion = strtoupper($request['descripcion']);
        $tratamiento->paciente_id = $request['paciente_id'];
        $tratamiento->medico_id = $request['medico_id'];
        $tratamiento->terapia_id = $request['terapia_id'];
        $tratamiento->asignados = $request['asignados'];
        $tratamiento->restantes = $request['asignados'];

        if($tratamiento->save()){
          $this->insertBitacoraTratamiento($request);
          Flash('¡El Tratamiento se ha agregado Exitosamente!')->success();
          return redirect()->intended('/sisa/calendario');
        }
    }

    public function show($id) {

    }

    public function edit($id) {

      $tratamiento = Tratamiento::findOrFail($id);

      if($tratamiento == null && count($tratamiento)== 0){
        return redirect()->intended('/sisa/tratamiento-management');
      }

      $pacientes = Paciente::select('pacientes.*')->orderBy('nombre1','asc')->get();
      $medicos = Medico::select('medicos.*')->orderBy('nombre','asc')->get();
      $terapias = Terapia::select('terapias.*')->where('id', '!=', 1)->orderBy('nombre','asc')->get();

      return view('tratamiento-mgmt/edit',['tratamiento' => $tratamiento, 'pacientes' => $pacientes, 'medicos' => $medicos, 'terapias' => $terapias]);
    }

    public function update(Request $request, $id) {
      $tratamiento = Tratamiento::find($id);
      if($request['asignados']!=0){
        $new_asignado = $request['asignados'] + $tratamiento->asignados;
        $new_restante = $request['asignados'] + $tratamiento->restantes;
        $tratamiento->asignados = $new_asignado;
        $tratamiento->restantes = $new_restante;
      }
      $this->validateUpdateTratamiento($request);
      $tratamiento->descripcion = strtoupper($request['descripcion']);
      $tratamiento->paciente_id = $request['paciente_id'];
      $tratamiento->medico_id = $request['medico_id'];
      $tratamiento->terapia_id = $request['terapia_id'];
      $this->updateBitacoraTratamiento($id, $request);

      if($tratamiento->save()){
        Flash('¡El Tratamiento se ha actualizado Exitosamente!')->success();
        return redirect()->intended('/sisa/tratamiento-management');
      }
    }

    public function search(Request $request) {
        $constraints = [
            'nombre1' => strtoupper ($request['nombre1']),
            'fechaInicio' => $request['fecha_inicio'],
            'fechaFin' => $request['fecha_fin']
        ];
  
        $nombre = strtoupper($request['nombre1']);
        
        $fechaInicio = $request['fecha_inicio'];
        $fechaFin = $request['fecha_fin'];

        if($request['nombre1']!=''){
          $tratamientos = DB::table('tratamientos')
            ->leftJoin('medicos', 'tratamientos.medico_id', '=', 'medicos.id')
            ->leftJoin('pacientes', 'tratamientos.paciente_id', '=', 'pacientes.id')
            ->leftJoin('terapias', 'tratamientos.terapia_id', '=', 'terapias.id')
            ->select(DB::raw('terapias.color as color,
                              terapias.nombre as nombre_terapia, 
                              medicos.nombre as nombre_medico,
                              pacientes.nombre1 as primer_nombre,
                              pacientes.nombre2 as segundo_nombre,
                              pacientes.nombre3 as tercer_nombre,
                              pacientes.apellido1 as primer_apellido,
                              pacientes.apellido2 as segundo_apellido,
                              pacientes.apellido3 as tercer_apellido,
                              tratamientos.*'))
            ->whereRaw("(terapias.nombre like '%$nombre%')")
            ->orWhereRaw("(pacientes.nombre1 like '%$nombre%')")
            ->orWhereRaw("(pacientes.nombre2 like '%$nombre%')")
            ->orWhereRaw("(pacientes.nombre3 like '%$nombre%')")
            ->orWhereRaw("(CONCAT(pacientes.nombre1,' ',pacientes.nombre2) like '%$nombre%')")
            ->orWhereRaw("(CONCAT(pacientes.nombre1,' ',pacientes.nombre2,' ',pacientes.nombre3) like '%$nombre%')")
            ->orWhereRaw("(CONCAT(pacientes.nombre1,' ',pacientes.apellido1) like '%$nombre%')")
            ->orWhereRaw("(CONCAT(pacientes.nombre1,' ',pacientes.nombre2,' ',pacientes.apellido1) like '%$nombre%')")
            ->orWhereRaw("(medicos.nombre like '%$nombre%')")
            ->paginate(10);
        } 

          else if($this->validar_fecha($fechaInicio)
            &&$this->validar_fecha($fechaFin)){
            $tratamientos = DB::table('tratamientos')
            ->leftJoin('medicos', 'tratamientos.medico_id', '=', 'medicos.id')
            ->leftJoin('pacientes', 'tratamientos.paciente_id', '=', 'pacientes.id')
            ->leftJoin('terapias', 'tratamientos.terapia_id', '=', 'terapias.id')
            ->select(DB::raw('terapias.color as color,
                              terapias.nombre as nombre_terapia, 
                              medicos.nombre as nombre_medico,
                              pacientes.nombre1 as primer_nombre,
                              pacientes.nombre2 as segundo_nombre,
                              pacientes.nombre3 as tercer_nombre,
                              pacientes.apellido1 as primer_apellido,
                              pacientes.apellido2 as segundo_apellido,
                              pacientes.apellido3 as tercer_apellido,
                              tratamientos.*'))
            ->whereRaw("(tratamientos.fecha::text like '%$fechaInicio%')")
            ->whereRaw("(tratamientos.fecha::text like '%$fechaFin%')")
            ->orWhereBetween('tratamientos.fecha', [$fechaInicio, $fechaFin])
            ->paginate(10);
          }
          else{
            $tratamientos = DB::table('tratamientos')
            ->leftJoin('medicos', 'tratamientos.medico_id', '=', 'medicos.id')
            ->leftJoin('pacientes', 'tratamientos.paciente_id', '=', 'pacientes.id')
            ->leftJoin('terapias', 'tratamientos.terapia_id', '=', 'terapias.id')
            ->select(DB::raw('terapias.color as color,
                              terapias.nombre as nombre_terapia, 
                              medicos.nombre as nombre_medico,
                              pacientes.nombre1 as primer_nombre,
                              pacientes.nombre2 as segundo_nombre,
                              pacientes.nombre3 as tercer_nombre,
                              pacientes.apellido1 as primer_apellido,
                              pacientes.apellido2 as segundo_apellido,
                              pacientes.apellido3 as tercer_apellido,
                              tratamientos.*'))
            ->paginate(10);
          }
        
        $message = ' ';
        return view('tratamiento-mgmt/index', ['tratamientos' => $tratamientos, 'searchingVals' => $constraints, 'message' => $message]);
    }

    private function validar_fecha($fecha){
      $valores = explode('-', $fecha);
      if((count($valores) == 3 && checkdate($valores[1], $valores[2], $valores[0]))
        ||($fecha==null)) return true;
        return false;
    }

    private function validateInsertTratamiento($request){
      $this->validate($request, [
          'descripcion' => 'max:500',
          'paciente_id' => 'required',
          'medico_id' => 'required',
          'terapia_id' => 'required'
      ]);
    }

    private function validateUpdateTratamiento($request){
      $this->validate($request, [
          'descripcion' => 'max:500',
          'paciente_id' => 'required',
          'medico_id' => 'required',
          'terapia_id' => 'required'
      ]);
    }

    private function insertBitacoraTratamiento($request){
      date_default_timezone_set('america/guatemala');
      $format = 'd/m/Y';
      $now = date($format);
      $log = Auth::user()->username;

      $paciente = Paciente::findOrFail($request['paciente_id']);
      $medico = Medico::findOrFail($request['medico_id']);
      $terapia = Terapia::findOrFail($request['terapia_id']);

      $data = 'Paciente: ' . $paciente->nombre1 .' '. $paciente->nombre2 .' '. $paciente->nombre3 .' '. $paciente->apellido1 .' '. $paciente->apellido2 .' '. $paciente->apellido3 . ', Médico: ' . $medico->nombre . ', Terapia: ' . $terapia->nombre . ', Fecha: ' . $now . ', Cantidad de Citas: ' . $request['asignados'];

          $bitacora = new Bitacora();
          $bitacora->usuario = $log;
          $bitacora->nombre_tabla = 'TRATAMIENTO';
          $bitacora->actividad = 'CREAR';
          $bitacora->anterior = '';
          $bitacora->nuevo = $data;
          $bitacora->fecha = $now;
          $bitacora->save();
    }

    private function updateBitacoraTratamiento($id, $request){
      date_default_timezone_set('america/guatemala');
      $format = 'd/m/Y';
      $now = date($format);
      $log = Auth::user()->username;

      $tratamiento = Tratamiento::findOrFail($id);
      $pacientenew = Paciente::findOrFail($request['paciente_id']);
      $pacienteold = Paciente::findOrFail($tratamiento->paciente_id);
      $mediconew = Medico::findOrFail($request['medico_id']);
      $medicoold = Medico::findOrFail($tratamiento->medico_id);
      $terapianew = Terapia::findOrFail($request['terapia_id']);
      $terapiaold = Terapia::findOrFail($tratamiento->terapia_id);

      if($tratamiento->paciente_id != $request['paciente_id']){
          $bitacora = new Bitacora();
          $bitacora->usuario = $log;
          $bitacora->nombre_tabla = 'TRATAMIENTO';
          $bitacora->actividad = 'CREAR';
          $bitacora->anterior = 'Paciente: ' . $pacienteold->nombre1 .' '.$pacienteold->nombre2.' '.$pacienteold->nombre3.' '.$pacienteold->apellido1.' '.$pacienteold->apellido2.' '.$pacienteold->apellido3;
          $bitacora->nuevo = 'Paciente: ' . $pacientenew->nombre1 .' '.$pacientenew->nombre2.' '.$pacientenew->nombre3.' '.$pacientenew->apellido1.' '.$pacientenew->apellido2.' '.$pacientenew->apellido3;
          $bitacora->fecha = $now;
          $bitacora->save();
      }

      if($tratamiento->medico_id != $request['medico_id']){
          $bitacora = new Bitacora();
          $bitacora->usuario = $log;
          $bitacora->nombre_tabla = 'TRATAMIENTO';
          $bitacora->actividad = 'CREAR';
          $bitacora->anterior = 'Médico: ' . $medicoold->nombre;
          $bitacora->nuevo = 'Médico: ' . $mediconew->nombre;
          $bitacora->fecha = $now;
          $bitacora->save();
      }

      if($tratamiento->terapia_id != $request['terapia_id']){
          $bitacora = new Bitacora();
          $bitacora->usuario = $log;
          $bitacora->nombre_tabla = 'TRATAMIENTO';
          $bitacora->actividad = 'CREAR';
          $bitacora->anterior = 'Médico: ' . $terapiaold->nombre;
          $bitacora->nuevo = 'Médico: ' . $terapianew->nombre;
          $bitacora->fecha = $now;
          $bitacora->save();
      }

      if($request['asignados']!=0){
        $new_asignado = $request['asignados'] + $tratamiento->asignados;
        $bitacora = new Bitacora();
        $bitacora->usuario = $log;
        $bitacora->nombre_tabla = 'TRATAMIENTO';
        $bitacora->actividad = 'CREAR';
        $bitacora->anterior = 'Cantidad de Citas: ' . $tratamiento->asignados;
        $bitacora->nuevo = 'Cantidad de Citas: ' . $new_asignado;
        $bitacora->fecha = $now;
        $bitacora->save();
      }
    }
}
