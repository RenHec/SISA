<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Paciente;
use App\Departamento;
use App\Municipio;
use App\Pago;
use Excel;
use Auth;
use PDF;

class ReportPacienteController extends Controller {

	    public function __construct() {
	        $this->middleware('auth');
	    }

	    public function index() {
	        date_default_timezone_set('america/guatemala');
	        $format = 'd/m/Y';
	        $now = date($format);
	        $to = date($format, strtotime("-366 days"));
	        $constraints = [
		            'from' => $to,
		            'to' => $now,
								'departamento' => '',
								'municipio' => '',
								'pago' => ''
	        ];
					$departamentos = Departamento::select('id', 'nombre')->orderBy('nombre', 'asc')->get();
	        $municipios = Municipio::select('id', 'nombre','departamento_id')->orderBy('nombre', 'asc')->get();
	        $pagos = Pago::select('id', 'nombre')->orderBy('nombre', 'asc')->get();
	        $pacientes = $this->getRangoPaciente($constraints);
	        $message = '';
	        return view('system-mgmt/report-paciente/index', ['pacientes' => $pacientes, 'departamentos' => $departamentos, 'municipios' => $municipios, 'pagos' => $pagos, 'searchingVals' => $constraints, 'message' => $message]);
	    }

	    public function search(Request $request) {
					$departamentos = Departamento::select('id', 'nombre')->orderBy('nombre', 'asc')->get();
					$municipios = Municipio::select('id', 'nombre','departamento_id')->orderBy('nombre', 'asc')->get();
					$pagos = Pago::select('id', 'nombre')->orderBy('nombre', 'asc')->get();

					if($request->from != '' && $request->to != ''){
						$constraints = [
									'from' => $request['from'],
									'to' =>$request['to'],
									'departamento' => $request['departamento_id'],
									'municipio' => $request['municipio_id'],
									'pago' => $request['pago_id'],
						];
						$pacientes = $this->getRangoPaciente($constraints);
	          $message = '';
	          return view('system-mgmt/report-paciente/index', ['pacientes' => $pacientes, 'departamentos' => $departamentos, 'municipios' => $municipios, 'pagos' => $pagos, 'searchingVals' => $constraints, 'message' => $message]);
	        }

	        if($request->from == '' || $request->to == ''){
						$constraints = [
									'from' => $request['from'],
									'to' =>$request['to'],
									'departamento' => $request['departamento_id'],
									'municipio' => $request['municipio_id'],
									'pago' => $request['pago_id'],
						];
	          $pacientes = $this->getRangoPaciente($constraints);
	          $message = 'Rango de Fecha inválido';
	          return view('system-mgmt/report-paciente/index', ['pacientes' => $pacientes, 'departamentos' => $departamentos, 'municipios' => $municipios, 'pagos' => $pagos, 'searchingVals' => $constraints, 'message' => $message]);
	        }
	    }

	    private function getRangoPaciente($constraints) {
					if($constraints['from'] == '' && $constraints['to'] == ''){
						if($constraints['departamento']!=0 && $constraints['pago']!=0){
							$pacientes = Paciente::where('departamento_id', '=', $constraints['departamento'])
																		->where('pago_id', '=', $constraints['pago'])->get();
							return $pacientes;
						}
						if($constraints['municipio']!=0 && $constraints['pago']!=0){
							$pacientes = Paciente::where('municipio_id', '=', $constraints['municipio'])
																		->where('pago_id', '=', $constraints['pago'])->get();
							return $pacientes;
						}
						if($constraints['departamento']!=0){
							$pacientes = Paciente::where('departamento_id', '=', $constraints['departamento'])->get();
							return $pacientes;
						}
						if($constraints['municipio']!=0){
							$pacientes = Paciente::where('municipio_id', '=', $constraints['municipio'])->get();
							return $pacientes;
						}
					}

	        if($constraints['from'] != '' && $constraints['to'] != ''){
						if($constraints['departamento']!=0 && $constraints['pago']!=0){
							$pacientes = Paciente::where('departamento_id', '=', $constraints['departamento'])
																		->where('pago_id', '=', $constraints['pago'])
																		->where('fecha_ingreso', '>=', $constraints['from'])
													          ->where('fecha_ingreso', '<=', $constraints['to'])
													          ->get();
							return $pacientes;
						}
						if($constraints['municipio']!=0 && $constraints['pago']!=0){
							$pacientes = Paciente::where('municipio_id', '=', $constraints['municipio'])
																		->where('pago_id', '=', $constraints['pago'])
																		->where('fecha_ingreso', '>=', $constraints['from'])
													          ->where('fecha_ingreso', '<=', $constraints['to'])
													          ->get();
							return $pacientes;
						}
						if($constraints['departamento']!=0){
							$pacientes = Paciente::where('departamento_id', '=', $constraints['departamento'])
																		->where('fecha_ingreso', '>=', $constraints['from'])
																		->where('fecha_ingreso', '<=', $constraints['to'])
																		->get();
							return $pacientes;
						}
						if($constraints['municipio']!=0){
							$pacientes = Paciente::where('municipio_id', '=', $constraints['municipio'])
																		->where('fecha_ingreso', '>=', $constraints['from'])
																		->where('fecha_ingreso', '<=', $constraints['to'])
																		->get();
							return $pacientes;
						}
						if($constraints['municipio']==0 && $constraints['departamento']==0 && $constraints['pago']==0){
							$pacientes = Paciente::where('fecha_ingreso', '>=', $constraints['from'])
																		->where('fecha_ingreso', '<=', $constraints['to'])
																		->get();
							return $pacientes;
						}
					}
	    }

	    public function exportExcel(Request $request) {
	        $this->prepareExportingData($request)->export('xlsx');
	        redirect()->intended('system-management/report-paciente');
	    }

	    public function exportPDF(Request $request) {
					date_default_timezone_set('america/guatemala');
					$format = 'Y-m-d H:i:s';
					$now = date($format);
					$constraints = [
								'from' => $request['from'],
								'to' =>$request['to'],
								'departamento' => $request['departamento_id'],
								'municipio' => $request['municipio_id'],
								'pago' => $request['pago_id'],
					];
	        $pacientes = $this->getExportingData($constraints);
	        $pdf = PDF::loadView('system-mgmt/report-paciente/pdf', ['pacientes' => $pacientes, 'searchingVals' => $constraints]);
	        return $pdf->download('reporte_fecha_'. $now .'.pdf');
	        return view('system-mgmt/report-paciente/pdf', ['pacientes' => $pacientes, 'searchingVals' => $constraints]);
	    }

	    private function prepareExportingData($request) {
					date_default_timezone_set('america/guatemala');
					$format = 'Y-m-d H:i:s';
					$now = date($format);
	        $author = Auth::user()->username;
	        $pacientes = $this->getExportingData(['from'=> $request['from'],
																								'to' => $request['to'],
																								'departamento' => $request['departamento'],
																								'municipio' => $request['municipio'],
																								'pago' => $request['pago']]);
	        return Excel::create('reporte_de_fecha_'. $now, function($excel) use($pacientes, $request, $author) {
						date_default_timezone_set('america/guatemala');
						$format = 'Y-m-d H:i:s';
						$now = date($format);
		        $excel->setTitle('Reporte de Pacientes del '. $now);
		        $excel->setCreator($author)->setCompany('HoaDang');
		        $excel->setDescription('Listado de Pacientes');
		        $excel->sheet('Reporte', function($sheet) use($pacientes) {
		        	$sheet->fromArray($pacientes);
	          });
	        });
	    }

	    private function getExportingData($constraints) {
				if($constraints['from'] == '' && $constraints['to'] == ''){
					if($constraints['departamento']!=0 && $constraints['pago']!=0){
							return DB::table('pacientes')
			        ->leftJoin('departamentos', 'pacientes.departamento_id', '=', 'departamentos.id')
			        ->leftJoin('municipios', 'pacientes.municipio_id', '=', 'municipios.id')
			        ->leftJoin('pagos', 'pacientes.pago_id', '=', 'pagos.id')
			        ->select('pacientes.cui as CUI',
												'pacientes.nombre1 as Primer_Nombre',
												'pacientes.nombre2 as Segundo_Nombre',
												'pacientes.nombre3 as Tercer_Nombre',
												'pacientes.apellido1 as Primer_Apellido',
												'pacientes.apellido2 as Segundo_Apellido',
												'pacientes.apellido3 as Tercer_Apellido',
												'departamentos.nombre as Departamento',
												'municipios.nombre as Municipio',
												'pacientes.direccion as Dirección',
												'pacientes.fecha_nacimiento as Fecha_Nacimiento',
												'pacientes.encargado as Nombre_Encargado',
												'pacientes.fecha_ingreso as Fecha_Ingreso',
												'pacientes.telefono as Teléfono',
												'pacientes.seguro_social as No_Seguro_Social',
												'pagos.nombre as Tipo_Pago')
			        ->where('pacientes.departamento_id', '=', $constraints['departamento'])
			        ->where('pacientes.pago_id', '=', $constraints['pago'])
			        ->get()
			        ->map(function ($item, $key) {
			        return (array) $item;
			        })
			        ->all();
					}
					if($constraints['municipio']!=0 && $constraints['pago']!=0){
							return DB::table('pacientes')
							->leftJoin('departamentos', 'pacientes.departamento_id', '=', 'departamentos.id')
							->leftJoin('municipios', 'pacientes.municipio_id', '=', 'municipios.id')
							->leftJoin('pagos', 'pacientes.pago_id', '=', 'pagos.id')
							->select('pacientes.cui as CUI',
												'pacientes.nombre1 as Primer_Nombre',
												'pacientes.nombre2 as Segundo_Nombre',
												'pacientes.nombre3 as Tercer_Nombre',
												'pacientes.apellido1 as Primer_Apellido',
												'pacientes.apellido2 as Segundo_Apellido',
												'pacientes.apellido3 as Tercer_Apellido',
												'departamentos.nombre as Departamento',
												'municipios.nombre as Municipio',
												'pacientes.direccion as Dirección',
												'pacientes.fecha_nacimiento as Fecha_Nacimiento',
												'pacientes.encargado as Nombre_Encargado',
												'pacientes.fecha_ingreso as Fecha_Ingreso',
												'pacientes.telefono as Teléfono',
												'pacientes.seguro_social as No_Seguro_Social',
												'pagos.nombre as Tipo_Pago')
							->where('pacientes.municipio_id', '=', $constraints['municipio'])
							->where('pacientes.pago_id', '=', $constraints['pago'])
							->get()
							->map(function ($item, $key) {
							return (array) $item;
							})
							->all();
					}
					if($constraints['departamento']!=0){
							return DB::table('pacientes')
							->leftJoin('departamentos', 'pacientes.departamento_id', '=', 'departamentos.id')
							->leftJoin('municipios', 'pacientes.municipio_id', '=', 'municipios.id')
							->leftJoin('pagos', 'pacientes.pago_id', '=', 'pagos.id')
							->select('pacientes.cui as CUI',
												'pacientes.nombre1 as Primer_Nombre',
												'pacientes.nombre2 as Segundo_Nombre',
												'pacientes.nombre3 as Tercer_Nombre',
												'pacientes.apellido1 as Primer_Apellido',
												'pacientes.apellido2 as Segundo_Apellido',
												'pacientes.apellido3 as Tercer_Apellido',
												'departamentos.nombre as Departamento',
												'municipios.nombre as Municipio',
												'pacientes.direccion as Dirección',
												'pacientes.fecha_nacimiento as Fecha_Nacimiento',
												'pacientes.encargado as Nombre_Encargado',
												'pacientes.fecha_ingreso as Fecha_Ingreso',
												'pacientes.telefono as Teléfono',
												'pacientes.seguro_social as No_Seguro_Social',
												'pagos.nombre as Tipo_Pago')
							->where('pacientes.departamento_id', '=', $constraints['departamento'])
							->get()
							->map(function ($item, $key) {
							return (array) $item;
							})
							->all();
					}
					if($constraints['municipio']!=0){
							return DB::table('pacientes')
							->leftJoin('departamentos', 'pacientes.departamento_id', '=', 'departamentos.id')
							->leftJoin('municipios', 'pacientes.municipio_id', '=', 'municipios.id')
							->leftJoin('pagos', 'pacientes.pago_id', '=', 'pagos.id')
							->select('pacientes.cui as CUI',
												'pacientes.nombre1 as Primer_Nombre',
												'pacientes.nombre2 as Segundo_Nombre',
												'pacientes.nombre3 as Tercer_Nombre',
												'pacientes.apellido1 as Primer_Apellido',
												'pacientes.apellido2 as Segundo_Apellido',
												'pacientes.apellido3 as Tercer_Apellido',
												'departamentos.nombre as Departamento',
												'municipios.nombre as Municipio',
												'pacientes.direccion as Dirección',
												'pacientes.fecha_nacimiento as Fecha_Nacimiento',
												'pacientes.encargado as Nombre_Encargado',
												'pacientes.fecha_ingreso as Fecha_Ingreso',
												'pacientes.telefono as Teléfono',
												'pacientes.seguro_social as No_Seguro_Social',
												'pagos.nombre as Tipo_Pago')
							->where('pacientes.municipio_id', '=', $constraints['municipio'])
							->get()
							->map(function ($item, $key) {
							return (array) $item;
							})
							->all();
					}
				}

				if($constraints['from'] != '' && $constraints['to'] != ''){
					if($constraints['departamento']!=0 && $constraints['pago']!=0){
							return DB::table('pacientes')
							->leftJoin('departamentos', 'pacientes.departamento_id', '=', 'departamentos.id')
							->leftJoin('municipios', 'pacientes.municipio_id', '=', 'municipios.id')
							->leftJoin('pagos', 'pacientes.pago_id', '=', 'pagos.id')
							->select('pacientes.cui as CUI',
												'pacientes.nombre1 as Primer_Nombre',
												'pacientes.nombre2 as Segundo_Nombre',
												'pacientes.nombre3 as Tercer_Nombre',
												'pacientes.apellido1 as Primer_Apellido',
												'pacientes.apellido2 as Segundo_Apellido',
												'pacientes.apellido3 as Tercer_Apellido',
												'departamentos.nombre as Departamento',
												'municipios.nombre as Municipio',
												'pacientes.direccion as Dirección',
												'pacientes.fecha_nacimiento as Fecha_Nacimiento',
												'pacientes.encargado as Nombre_Encargado',
												'pacientes.fecha_ingreso as Fecha_Ingreso',
												'pacientes.telefono as Teléfono',
												'pacientes.seguro_social as No_Seguro_Social',
												'pagos.nombre as Tipo_Pago')
						  ->where('pacientes.departamento_id', '=', $constraints['departamento'])
							->where('pacientes.pago_id', '=', $constraints['pago'])
							->where('fecha_ingreso', '>=', $constraints['from'])
							->where('fecha_ingreso', '<=', $constraints['to'])
							->get()
							->map(function ($item, $key) {
							return (array) $item;
							})
							->all();
					}
					if($constraints['municipio']!=0 && $constraints['pago']!=0){
							return DB::table('pacientes')
							->leftJoin('departamentos', 'pacientes.departamento_id', '=', 'departamentos.id')
							->leftJoin('municipios', 'pacientes.municipio_id', '=', 'municipios.id')
							->leftJoin('pagos', 'pacientes.pago_id', '=', 'pagos.id')
							->select('pacientes.cui as CUI',
												'pacientes.nombre1 as Primer_Nombre',
												'pacientes.nombre2 as Segundo_Nombre',
												'pacientes.nombre3 as Tercer_Nombre',
												'pacientes.apellido1 as Primer_Apellido',
												'pacientes.apellido2 as Segundo_Apellido',
												'pacientes.apellido3 as Tercer_Apellido',
												'departamentos.nombre as Departamento',
												'municipios.nombre as Municipio',
												'pacientes.direccion as Dirección',
												'pacientes.fecha_nacimiento as Fecha_Nacimiento',
												'pacientes.encargado as Nombre_Encargado',
												'pacientes.fecha_ingreso as Fecha_Ingreso',
												'pacientes.telefono as Teléfono',
												'pacientes.seguro_social as No_Seguro_Social',
												'pagos.nombre as Tipo_Pago')
							->where('pacientes.municipio_id', '=', $constraints['municipio'])
							->where('pacientes.pago_id', '=', $constraints['pago'])
							->where('fecha_ingreso', '>=', $constraints['from'])
							->where('fecha_ingreso', '<=', $constraints['to'])
							->get()
							->map(function ($item, $key) {
							return (array) $item;
							})
							->all();
					}
					if($constraints['departamento']!=0){
							return DB::table('pacientes')
							->leftJoin('departamentos', 'pacientes.departamento_id', '=', 'departamentos.id')
							->leftJoin('municipios', 'pacientes.municipio_id', '=', 'municipios.id')
							->leftJoin('pagos', 'pacientes.pago_id', '=', 'pagos.id')
							->select('pacientes.cui as CUI',
												'pacientes.nombre1 as Primer_Nombre',
												'pacientes.nombre2 as Segundo_Nombre',
												'pacientes.nombre3 as Tercer_Nombre',
												'pacientes.apellido1 as Primer_Apellido',
												'pacientes.apellido2 as Segundo_Apellido',
												'pacientes.apellido3 as Tercer_Apellido',
												'departamentos.nombre as Departamento',
												'municipios.nombre as Municipio',
												'pacientes.direccion as Dirección',
												'pacientes.fecha_nacimiento as Fecha_Nacimiento',
												'pacientes.encargado as Nombre_Encargado',
												'pacientes.fecha_ingreso as Fecha_Ingreso',
												'pacientes.telefono as Teléfono',
												'pacientes.seguro_social as No_Seguro_Social',
												'pagos.nombre as Tipo_Pago')
							->where('pacientes.departamento_id', '=', $constraints['departamento'])
							->where('fecha_ingreso', '>=', $constraints['from'])
							->where('fecha_ingreso', '<=', $constraints['to'])
							->get()
							->map(function ($item, $key) {
							return (array) $item;
							})
							->all();
					}
					if($constraints['municipio']!=0){
							return DB::table('pacientes')
							->leftJoin('departamentos', 'pacientes.departamento_id', '=', 'departamentos.id')
							->leftJoin('municipios', 'pacientes.municipio_id', '=', 'municipios.id')
							->leftJoin('pagos', 'pacientes.pago_id', '=', 'pagos.id')
							->select('pacientes.cui as CUI',
												'pacientes.nombre1 as Primer_Nombre',
												'pacientes.nombre2 as Segundo_Nombre',
												'pacientes.nombre3 as Tercer_Nombre',
												'pacientes.apellido1 as Primer_Apellido',
												'pacientes.apellido2 as Segundo_Apellido',
												'pacientes.apellido3 as Tercer_Apellido',
												'departamentos.nombre as Departamento',
												'municipios.nombre as Municipio',
												'pacientes.direccion as Dirección',
												'pacientes.fecha_nacimiento as Fecha_Nacimiento',
												'pacientes.encargado as Nombre_Encargado',
												'pacientes.fecha_ingreso as Fecha_Ingreso',
												'pacientes.telefono as Teléfono',
												'pacientes.seguro_social as No_Seguro_Social',
												'pagos.nombre as Tipo_Pago')
							->where('pacientes.municipio_id', '=', $constraints['municipio'])
							->where('fecha_ingreso', '>=', $constraints['from'])
							->where('fecha_ingreso', '<=', $constraints['to'])
							->get()
							->map(function ($item, $key) {
							return (array) $item;
							})
							->all();
					}
					if($constraints['municipio']==0 && $constraints['departamento']==0 && $constraints['pago']==0){
							return DB::table('pacientes')
			        ->leftJoin('departamentos', 'pacientes.departamento_id', '=', 'departamentos.id')
			        ->leftJoin('municipios', 'pacientes.municipio_id', '=', 'municipios.id')
			        ->leftJoin('pagos', 'pacientes.pago_id', '=', 'pagos.id')
			        ->select('pacientes.cui as CUI',
												'pacientes.nombre1 as Primer_Nombre',
												'pacientes.nombre2 as Segundo_Nombre',
												'pacientes.nombre3 as Tercer_Nombre',
												'pacientes.apellido1 as Primer_Apellido',
												'pacientes.apellido2 as Segundo_Apellido',
												'pacientes.apellido3 as Tercer_Apellido',
												'departamentos.nombre as Departamento',
												'municipios.nombre as Municipio',
												'pacientes.direccion as Dirección',
												'pacientes.fecha_nacimiento as Fecha_Nacimiento',
												'pacientes.encargado as Nombre_Encargado',
												'pacientes.fecha_ingreso as Fecha_Ingreso',
												'pacientes.telefono as Teléfono',
												'pacientes.seguro_social as No_Seguro_Social',
												'pagos.nombre as Tipo_Pago')
			        ->where('fecha_ingreso', '>=', $constraints['from'])
			        ->where('fecha_ingreso', '<=', $constraints['to'])
			        ->get()
			        ->map(function ($item, $key) {
			        return (array) $item;
			        })
			        ->all();
					}
				}
	    }
}
