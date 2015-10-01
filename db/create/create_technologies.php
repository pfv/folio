<?php

require '../../classes/Database.php';
require '../../classes/Validator.php';
require '../../classes/ErrorHandler.php';
require '../../classes/AdminGui.php';
require '../../functions/security.php';

$errorHandler = new ErrorHandler;

$db = new Database;
$gui = new AdminGui($db);

$technologies_records = $gui->select('technologies');

if(!empty($_POST)){
	$db->table('technologies');
	$validator = new Validator($db, $errorHandler);
	$validation = $validator->check($_POST, [
		'technology' => [
			'required'=>true
		]
	]);

	if($validation->fails()){
		echo '<pre>',print_r($validation->errors()->all()),'</pre>';
	}
	else{
		if($db->insert($_POST)){
			header('Location: create_technologies.php');
			die();
		}
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Create technologies</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body style="font-family:Helvetica;font-size:12px">
		<h3>Technologies</h3>

		<?php
		if(!count($technologies_records)){
			echo 'Tabela vazia';
		}
		else{
		?>

			<table>
				<thead>
					<tr>
						<th class="id_field">id</th>
						<th>technology</th>
					</tr>
				</thead>
				<tbody>

					<?php
					foreach($technologies_records as $tr){
					?>
						<tr>
							<?php
								$gui->showElement($tr,'id');
								$gui->showElement($tr,'technology');
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
			<h4>Create new technology</h4>
			<?php
				$gui->inputText('technology');
			?>
			<input type="submit" value="Inserir">
		</form>

	</body>
</html>






