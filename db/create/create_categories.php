<?php

require '../../classes/Database.php';
require '../../classes/Validator.php';
require '../../classes/ErrorHandler.php';
require '../../classes/AdminGui.php';
require '../../functions/security.php';

$errorHandler = new ErrorHandler;

$db = new Database;
$gui = new AdminGui($db);

$categories_records = $gui->select('categories');

if(!empty($_POST)){
	$db->table('categories');
	$validator = new Validator($db, $errorHandler);
	$validation = $validator->check($_POST, [
		'category' => [
			'required'=>true,
			'unique'=>'categories'
		]
	]);

	if($validation->fails()){
		echo '<pre>',print_r($validation->errors()->all()),'</pre>';
	}
	else{
		if($db->insert($_POST)){
			header('Location: create_categories.php');
			die();
		}
	}
}

?>

<!doctype html>
	<html>
	<head>
		<title>Create categories</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body style="font-family:Helvetica;font-size:12px">
		<h3>Categories</h3>

		<?php
		if(!count($categories_records)){
			echo 'Tabela vazia';
		}
		else{
		?>

			<table>
				<thead>
					<tr>
						<th class="id_field">id</th>
						<th>category</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach($categories_records as $cr){
					?>
						<tr>
							<?php
								$gui->showElement($cr,'id');
								$gui->showElement($cr,'category');
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
			<h4>Add new category</h4>
			<?php
				$gui->inputText('category');
			?>
			<input type="submit" value="Inserir">
		</form>

	</body>
</html>






