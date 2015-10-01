<?php

require '../../classes/Database.php';
require '../../classes/Validator.php';
require '../../classes/ErrorHandler.php';
require '../../classes/AdminGui.php';
require '../../functions/security.php';

$errorHandler = new ErrorHandler;

$db = new Database;
$gui = new AdminGui($db);

$places_records = $gui->select('places');

if(!empty($_POST)){
	$db->table('places');
	$validator = new Validator($db, $errorHandler);
	$validation = $validator->check($_POST, [
		'city' => [
			'required'=>true
		],
		'state' => [
			'required'=>false,
			'maxlength'=>2
		],
		'country' => [
			'required' => true
		]
	]);

	if($validation->fails()){
		echo '<pre>',print_r($validation->errors()->all()),'</pre>';
	}
	else{
		if($db->insert($_POST)){
			header('Location: create_places.php');
			die();
		}
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Create places</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body style="font-family:Helvetica;font-size:12px">
		<h3>Places</h3>

		<?php
		if(!count($places_records)){
			echo 'Tabela vazia';
		}
		else{
		?>

			<table>
				<thead>
					<tr>
						<th class="id_field">id</th>
						<th>city</th>
						<th>state</th>
						<th>country</th>
					</tr>
				</thead>
				<tbody>

					<?php
					foreach($places_records as $pr){
					?>
						<tr>
							<?php
								$gui->showElement($pr,'id');
								$gui->showElement($pr,'city');
								$gui->showElement($pr,'state');
								$gui->showElement($pr,'country');
							?>
						</tr>
					<?php
					}
					?>

				</tbody>
			</table>

		<?php
		}
		?>

		<form action="" method="post">
			<h4>Add new place</h4>
			<?php
				$gui->inputText('city');
				$gui->inputText('state');
				$gui->inputText('country');
			?>
			<input type="submit" value="Inserir">
		</form>

	</body>
</html>





