<?php

require '../../classes/Database.php';
require '../../classes/Validator.php';
require '../../classes/ErrorHandler.php';
require '../../classes/AdminGui.php';
require '../../functions/security.php';

$errorHandler = new ErrorHandler;

$db = new Database;
$gui = new AdminGui($db);

$people_records = $gui->select('people');

if(!empty($_POST)){
	$db->table('people');
	$validator = new Validator($db, $errorHandler);
	$validation = $validator->check($_POST, [
		'name' => [
			'required'=>true
		],
		'website' => [
			'required'=>false
		],
		'description' => [
			'required' => false
		]
	]);

	if($validation->fails()){
		echo '<pre>',print_r($validation->errors()->all()),'</pre>';
	}
	else{
		if($db->insert($_POST)){
			header('Location: create_people.php');
			die();
		}
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Create people</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body style="font-family:Helvetica;font-size:12px">
		<h3>People</h3>

		<?php
		if(!count($people_records)){
			echo 'Tabela vazia';
		}
		else{
		?>

			<table>
				<thead>
					<tr>
						<th class="id_field">id</th>
						<th>name</th>
						<th>website</th>
						<th>description</th>
					</tr>
				</thead>
				<tbody>

					<?php
					foreach($people_records as $pr){
					?>
						<tr>
							<?php
								$gui->showElement($pr,'id');
								$gui->showElement($pr,'name');
								$gui->showElement($pr,'website');
								$gui->showElement($pr,'description');
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
			<h4>Add new person</h4>
			<?php
				$gui->inputText('name');
				$gui->inputText('website');
				$gui->inputText('description');
			?>
			<input type="submit" value="Inserir">
		</form>

	</body>
</html>





