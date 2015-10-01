<?php

require '../../classes/Database.php';
require '../../classes/Validator.php';
require '../../classes/ErrorHandler.php';
require '../../classes/AdminGui.php';
require '../../functions/security.php';

$errorHandler = new ErrorHandler;

$db = new Database;
$gui = new AdminGui($db);

$functions_records = $gui->select('functions');

if(!empty($_POST)){
	$db->table('functions');
	$validator = new Validator($db, $errorHandler);
	$validation = $validator->check($_POST, [
		'function' => [
			'required'=>true,
			'unique'=>'functions'
		]
	]);

	if($validation->fails()){
		echo '<pre>',print_r($validation->errors()->all()),'</pre>';
	}
	else{
		if($db->insert($_POST)){
			header('Location: create_functions.php');
			die();
		}
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Create functions</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body style="font-family:Helvetica;font-size:12px">
		<h3>Functions</h3>

		<?php
		if(!count($functions_records)){
			echo 'Tabela vazia';
		}
		else{
		?>

			<table>
				<thead>
					<tr>
						<th class="id_field">id</th>
						<th>function</th>
					</tr>
				</thead>
				<tbody>

					<?php
					foreach($functions_records as $fr){
					?>
						<tr>
							<?php
								$gui->showElement($fr,'id');
								$gui->showElement($fr,'function');
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
			<h4>Add new function</h4>
			<?php
				$gui->inputText('function');
			?>
			<input type="submit" value="Inserir">
		</form>

	</body>
</html>






