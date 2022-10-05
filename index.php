<?php
require_once "vendor/autoload.php";
use CsvTo\Json;
$d = __DIR__.'/bk/';
$json = json_encode([
			"header" => [
				'ID', 'User', 'Name', 'LastName', 'Email', 'Date'
			],
			'line' => [
				[
					"ID" => "1",
					"User" => 'Usuario1',
					"Name" => "Pedro",
					"LastName" => "Perez",
					"Email" => "pedro@perez.com",
					"Date" => "12/10/22"
				],
				[
					"ID" => "2",
					"User" => 'Usuario2',
					"Name" => "Jhon",
					"LastName" => "Doe",
					"Email" => "jhondoe@gmail.com",
					"Date" => "28/02/22"
				]
			]
		]);
var_dump(Json::toArray($json));

?> 
<!--
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Cuaderno de Votación</title>
	<link rel="stylesheet" type="text/css" href="demo/build/css/metro-all.min.css">
</head>
<body>
	<table class="table striped" data-role="table">
		<thead>
			<tr>
				<th>#</th>
				<th>Apellido</th>
				<th>Nombre</th>
				<th class="sortable-column sort-asc">Cédula</th>
				<th class="sortable-column sort-asc">Edad</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			/*foreach ($data as $i => $person) {
				?>
			<tr>
				<td><?= $i + 1 ?></td>
				<td><?= ucwords($person['Apellido']) ?></td>
				<td><?= ucwords($person['Nombre']) ?></td>
				<td><?= $person['Nacionalidad']."-".$person['Cédula'] ?></td>
				<td><?= $person['Edad'] ?></td>
			</tr>
				<?php
			}*/
			?>
		</tbody>
	</table>	
</body>
</html>
-->