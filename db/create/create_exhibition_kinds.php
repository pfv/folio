<?php

require '../../classes/Database.php';
require '../../classes/Validator.php';
require '../../classes/ErrorHandler.php';
require '../../classes/AdminGui.php';
require '../../functions/security.php';

$errorHandler = new ErrorHandler;

$db = new Database;
$gui = new AdminGui($db);

$exhibition_kinds_records = $gui->select('exhibition_kinds');

if(!empty($_POST)){
	$db->table('exhibition_kinds');
	$validator = new Validator($db, $errorHandler);
	$validation = $validator->check($_POST, [
		'exhibition_kind' => [
			'required'=>true
		]
	]);

	if($validation->fails()){
		echo '<pre>',print_r($validation->errors()->all()),'</pre>';
	}
	else{
		if($db->insert($_POST)){
			header('Location: create_exhibition_kinds.php');
			die();
		}
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Create exhibition kinds</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body style="font-family:Helvetica;font-size:12px">
		<h3>Exhibition kinds</h3>

		<?php
		if(!count($exhibition_kinds_records)){
			echo 'Tabela vazia';
		}
		else{
		?>

			<table>
				<thead>
					<tr>
						<th class="id_field">id</th>
						<th>exhibition_kind</th>
					</tr>
				</thead>
				<tbody>

					<?php
					foreach($exhibition_kinds_records as $ekr){
					?>
						<tr>
							<?php
								$gui->showElement($ekr,'id');
								$gui->showElement($ekr,'exhibition_kind');
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
			<h4>Create exhibition kind</h4>
			<?php
				$gui->inputText('exhibition_kind');
			?>
			<input type="submit" value="Inserir">
		</form>

	</body>
</html>






