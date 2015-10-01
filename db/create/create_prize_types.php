<?php

require '../../classes/Database.php';
require '../../classes/Validator.php';
require '../../classes/ErrorHandler.php';
require '../../classes/AdminGui.php';
require '../../functions/security.php';

$errorHandler = new ErrorHandler;

$db = new Database;
$gui = new AdminGui($db);

$prize_types_records = $gui->select('prize_types');

if(!empty($_POST)){
	$db->table('prize_types');
	$validator = new Validator($db, $errorHandler);
	$validation = $validator->check($_POST, [
		'prize_type' => [
			'required'=>true,
			'unique'=>'prize_types'
		]
	]);

	if($validation->fails()){
		echo '<pre>',print_r($validation->errors()->all()),'</pre>';
	}
	else{
		if($db->insert($_POST)){
			header('Location: create_prize_types.php');
			die();
		}
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Create prize types</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body style="font-family:Helvetica;font-size:12px">
		<h3>Prize types</h3>

		<?php
		if(!count($prize_types_records)){
			echo 'Tabela vazia';
		}
		else{
		?>

			<table>
				<thead>
					<tr>
						<th class="id_field">id</th>
						<th>prize_type</th>
					</tr>
				</thead>
				<tbody>

					<?php
					foreach($prize_types_records as $ptr){
					?>
						<tr>
							<?php
								$gui->showElement($ptr,'id');
								$gui->showElement($ptr,'prize_type');
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
			<h4>Create new prize type</h4>
			<?php
				$gui->inputText('prize_type');
			?>
			<input type="submit" value="Inserir">
		</form>

	</body>
</html>






