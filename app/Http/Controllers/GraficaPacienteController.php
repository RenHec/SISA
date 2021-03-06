<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Paciente;
use App\Municipio;
use Charts;

class GraficaPacienteController extends Controller{
		public function __construct() {
				$this->middleware('auth');
		}

		public function index() {
			date_default_timezone_set('america/guatemala');
			$format = 'Y';
			$now = date($format);

			$municipalidad = Paciente::where('pago_id', '=', 1)->count('pago_id');
			$apadrinado = Paciente::where('pago_id', '=', 2)->count('pago_id');
			$prepago = Paciente::where('pago_id', '=', 3)->count('pago_id');
			$exonerado = Paciente::where('pago_id', '=', 4)->count('pago_id');

			$municipio = Paciente::join('municipios', 'pacientes.municipio_id', 'municipios.id')
												->select('pacientes.municipio_id')
												->where('municipios.departamento_id', '=', 18)->get();

			$group_municipio = Charts::database($municipio, 'bar', 'highcharts')
	        ->title('Pacientes por Municipio de Santa Rosa')
	        ->dimensions(10, 5)
	        ->responsive(true)
					->elementLabel("Cantidad")
					->groupBy('municipio_id', null, [267 => 'BARBERENA', 268 => 'CASILLAS', 269 => 'CHIQUIMULILLA',
																									270 => 'CUILAPA', 271 => 'GUAZACAPAN', 272 => 'NUEVA SANTA ROSA',
																									273 => 'ORATORIO', 274 => 'PUEBLO NUEVO VIÑAS', 275 => 'SAN JUAN TECUACO',
																									276 => 'SAN RAFAEL LAS FLORES', 277 => 'SANTA CRUZ NARANJO', 278 => 'SANTA MARIA IXHUATAN',
																									279 => 'SANTA ROSA DE LIMA', 280 => 'TAXISCO']);

			$group_tipo_pago = Charts::create('pie', 'highcharts')
					->title('Pacientes por Tipo de Pago')
					->labels(['APADRINADO', 'MUNICIPALIDAD', 'PREPAGO', 'EXONERADO'])
					->values([$apadrinado,$municipalidad,$prepago,$exonerado])
					->colors(['#00a01c', '#009b95', '#f1b401', '#2e3ec7'])
					->dimensions(1000,500)
					->responsive(true);

			$grafica_registro = Charts::multiDatabase('line', 'highcharts')
          ->title('Gráfica de Pacientes registrados en SISA')
          ->dataset('Pacientes', Paciente::all())
          ->responsive(true)
          ->elementLabel("Cantidad")
          ->groupByMonth($now, true);

      return view('grafica-mgmt/paciente/index', ['grafica_registro' => $grafica_registro, 'group_tipo_pago' => $group_tipo_pago, 'group_municipio' => $group_municipio]);
    }
}
