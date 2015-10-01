<?php

require '../../classes/Database.php';
require '../../classes/Validator.php';
require '../../classes/ErrorHandler.php';
require '../../classes/AdminGui.php';
require '../../functions/security.php';

$errorHandler = new ErrorHandler;

$db = new Database;
$gui = new AdminGui($db);

$project_types_records = $gui->select('project_types');

if(!empty($_POST)){
	$db->table('project_types');
	$validator = new Validator($db, $errorHandler);
	$validation = $validator->check($_POST, [
		'name' => [
			'required'=>true
		],
		'given_name' => [
			'required'=>true
		]
	]);

	if($validation->fails()){
		echo '<pre>',print_r($validation->errors()->all()),'</pre>';
	}
	else{
		if($db->insert($_POST)){
			header('Location: create_project_types.php');
			die();
		}
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Create project types</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body style="font-family:Helvetica;font-size:12px">
		<h3>Project_types</h3>

		<?php
		if(!count($project_types_records)){
			echo 'Tabela vazia';
		}
		else{
		?>

			<table>
				<thead>
					<tr>
						<th class="id_field">id</th>
						<th>name</th>
						<th>given_name</th>
					</tr>
				</thead>
				<tbody>

					<?php
					foreach($project_types_records as $ptr){
					?>
						<tr>
							<?php
								$gui->showElement($ptr,'id');
								$gui->showElement($ptr,'name');
								$gui->showElement($ptr,'given_name');
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
			<h4>Create new project type</h4>
			<?php
				$gui->inputText('name');
				$gui->inputText('given_name');
			?>
			<input type="submit" value="Inserir">
		</form>

	</body>
</html>






