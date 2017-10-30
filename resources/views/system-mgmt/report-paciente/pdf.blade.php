<!DOCTYPE html>
<html lang="en">
 <head>
	 <!-- Required meta tags -->
	 <meta charset="utf-8">
	 <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	 <style>
		 table {
			 border-collapse: collapse;
			 width: 100%;
		 }
		 td, th {
			 border: solid 2px;
			 padding: 10px 5px;
		 }
		 tr {
			 text-align: center;
		 }
		 .container {
			 width: 100%;
			 text-align: center;
		 }
	 </style>
 </head>
 <body>
	 <div class="container">
			 <div><h2>Reporte de Pacientes</h2></div>
			<table id="example2" role="grid">
					 <thead>
						 <tr role="row">
							 <th width="1%">CUI</th>
							 <th width="1%">Nombre Completo</th>
							 <th width="1%">Dirección</th>
							 <th width="1%">Fec. Nac.</th>
							 <th width="1%">Encargado y Télefono</th>
							 <th width="1%">Fecha Ingreso , No. Social y Tipo de Pago</th>
						 </tr>
					 </thead>
					 <tbody>
					 @foreach ($pacientes as $paciente)
							 <tr role="row" class="odd">
								 <td>{{ $paciente['CUI'] }}</td>
								 <td>{{ $paciente['Primer_Nombre'] }} {{ $paciente['Segundo_Nombre'] }} {{ $paciente['Tercer_Nombre'] }} {{ $paciente['Primer_Apellido'] }} {{ $paciente['Segundo_Apellido'] }} {{ $paciente['Tercer_Apellido'] }}</td>
								 <td>{{ $paciente['Municipio'] }}, {{ $paciente['Dirección'] }}</td>
								 <td>{{ $paciente['Fecha_Nacimiento'] }}</td>
								 <td>{{ $paciente['Nombre_Encargado'] }} - {{ $paciente['Teléfono'] }}</td>
								 <td>{{ $paciente['Fecha_Ingreso'] }} - {{ $paciente['No_Seguro_Social'] }} {{ $paciente['Tipo_Pago'] }}</td>
						 </tr>
					 @endforeach
					 </tbody>
				 </table>
	 </div>
 </body>
</html>
