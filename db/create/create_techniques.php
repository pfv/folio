<?php

require '../../classes/Database.php';
require '../../classes/Validator.php';
require '../../classes/ErrorHandler.php';
require '../../classes/AdminGui.php';
require '../../functions/security.php';

$errorHandler = new ErrorHandler;

$db = new Database;
$gui = new AdminGui($db);

$techniques_records = $gui->select('techniques');

if(!empty($_POST)){
	$db->table('techniques');
	$validator = new Validator($db, $errorHandler);
	$validation = $validator->check($_POST, [
		'technique' => [
			'required'=>true
		]
	]);

	if($validation->fails()){
		echo '<pre>',print_r($validation->errors()->all()),'</pre>';
	}
	else{
		if($db->insert($_POST)){
			header('Location: create_techniques.php');
			die();
		}
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Create techniques</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body style="font-family:Helvetica;font-size:12px">
		<h3>Techniques</h3>

		<?php
		if(!count($techniques_records)){
			echo 'Tabela vazia';
		}
		else{
		?>

			<table>
				<thead>
					<tr>
						<th class="id_field">id</th>
						<th>technique</th>
					</tr>
				</thead>
				<tbody>

					<?php
					foreach($techniques_records as $tr){
					?>
						<tr>
							<?php
								$gui->showElement($tr,'id');
								$gui->showElement($tr,'technique');
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
			<h4>Create new technique</h4>
			<?php
				$gui->inputText('technique');
			?>
			<input type="submit" value="Inserir">
		</form>

	</body>
</html>






