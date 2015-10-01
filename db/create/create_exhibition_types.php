<?php

require '../../classes/Database.php';
require '../../classes/Validator.php';
require '../../classes/ErrorHandler.php';
require '../../classes/AdminGui.php';
require '../../functions/security.php';

$errorHandler = new ErrorHandler;

$db = new Database;
$gui = new AdminGui($db);

$exhibition_types_records = $gui->select('exhibition_types');

if(!empty($_POST)){
	$db->table('exhibition_types');
	$validator = new Validator($db, $errorHandler);
	$validation = $validator->check($_POST, [
		'exhibition_type' => [
			'required'=>true
		]
	]);

	if($validation->fails()){
		echo '<pre>',print_r($validation->errors()->all()),'</pre>';
	}
	else{
		if($db->insert($_POST)){
			header('Location: create_exhibition_types.php');
			die();
		}
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Create exhibition types</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body style="font-family:Helvetica;font-size:12px">
		<h3>Exhibition types</h3>

		<?php
		if(!count($exhibition_types_records)){
			echo 'Tabela vazia';
		}
		else{
		?>

			<table>
				<thead>
					<tr>
						<th class="id_field">id</th>
						<th>exhibition_type</th>
					</tr>
				</thead>
				<tbody>

					<?php
					foreach($exhibition_types_records as $etr){
					?>
						<tr>
							<?php
								$gui->showElement($etr,'id');
								$gui->showElement($etr,'exhibition_type');
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
			<h4>Create new exhibition type</h4>
			<?php
				$gui->inputText('exhibition_type');
			?>
			<input type="submit" value="Inserir">
		</form>

	</body>
</html>






