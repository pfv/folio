<?php

require '../../classes/Database.php';
require '../../classes/Validator.php';
require '../../classes/ErrorHandler.php';
require '../../classes/AdminGui.php';
require '../../functions/security.php';

$errorHandler = new ErrorHandler;

$db = new Database;
$gui = new AdminGui($db);

$collection_types_records = $gui->select('collection_types');

if(!empty($_POST)){
	$db->table('collection_types');
	$validator = new Validator($db, $errorHandler);
	$validation = $validator->check($_POST, [
		'collection_type' => [
			'required'=>true
		]
	]);

	if($validation->fails()){
		echo '<pre>',print_r($validation->errors()->all()),'</pre>';
	}
	else{
		if($db->insert($_POST)){
			header('Location: create_collection_types.php');
			die();
		}
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Create collection types</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body style="font-family:Helvetica;font-size:12px">
		<h3>Collection types</h3>

		<?php
		if(!count($collection_types_records)){
			echo 'Tabela vazia';
		}
		else{
		?>

			<table>
				<thead>
					<tr>
						<th class="id_field">id</th>
						<th>collection_type</th>
					</tr>
				</thead>
				<tbody>

					<?php
					foreach($collection_types_records as $ctr){
					?>
						<tr>
							<?php
								$gui->showElement($ctr,'id');
								$gui->showElement($ctr,'collection_type');
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
			<h4>Add new collection type</h4>
			<?php
				$gui->inputText('collection_type');
			?>
			<input type="submit" value="Inserir">
		</form>

	</body>
</html>






