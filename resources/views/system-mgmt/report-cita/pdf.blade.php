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
			 <div><h2>Reporte de Cita</h2></div>
			<table id="example2" role="grid">
					 <thead>
						 <tr role="row">
							 <th width="10%">Nombre Completo</th>
               <th width="10%">Terapia</th>
							 <th width="4%">Citas Asignadas</th>
							 <th width="4%">Citas sin Asignar</th>
               <th width="10%">Médico</th>
               <th width="8%">Fecha de Cita</th>
						 </tr>
					 </thead>
					 <tbody>
					 @foreach ($citas as $cita)
							 <tr role="row" class="odd">
								 <td>{{ $cita['Primer_Nombre'] }} {{ $cita['Segundo_Nombre'] }} {{ $cita['Tercer_Nombre'] }} {{ $cita['Primer_Apellido'] }} {{ $cita['Segundo_Apellido'] }} {{ $cita['Tercer_Apellido'] }}</td>
                 <td>{{ $cita['Terapia'] }}</td>
								 <td>{{ $cita['Citas'] }}</td>
                 <td>{{ $cita['Restantes'] }}</td>
                 <td>{{ $cita['Medico'] }}</td>
                 <td>{{ $cita['Fecha'] }}</td>
						 </tr>
					 @endforeach
					 </tbody>
				 </table>
	 </div>
 </body>
</html>