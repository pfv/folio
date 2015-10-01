<?php

require '../../classes/Database.php';
require '../../classes/Validator.php';
require '../../classes/ErrorHandler.php';
require '../../classes/AdminGui.php';
require '../../functions/security.php';

$errorHandler = new ErrorHandler;

$db = new Database;
$gui = new AdminGui($db);

$companies_records = $gui->select('companies');

if(!empty($_POST)){
	$db->table('companies');
	$validator = new Validator($db, $errorHandler);
	$validation = $validator->check($_POST, [
		'company' => [
			'required'=>true
		],
		'website' => [
			'required'=>false
		],
		'description' => [
			'required'=>false
		],
		'year_start' => [
			'required'=>true
		],
		'year_end' => [
			'required'=>false
		],
	]);

	if($validation->fails()){
		echo '<pre>',print_r($validation->errors()->all()),'</pre>';
	}
	else{
		echo '<pre>',print_r($_POST),'</pre>';
		if($db->insert($_POST)){
			header('Location: create_companies.php');
			die();
		}
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Create companies</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body style="font-family:Helvetica;font-size:12px">
		<h3>Companies</h3>

		<?php
		if(!count($companies_records)){
			echo 'Tabela vazia';
		}
		else{
		?>

			<table>
				<thead>
					<tr>
						<th class="id_field">id</th>
						<th>company</th>
						<th>website</th>
						<th>description</th>
						<th>start</th>
						<th>end</th>
					</tr>
				</thead>
				<tbody>

					<?php
					foreach($companies_records as $cor){
					?>
						<tr>
							<?php
								$gui->showElement($cor,'id');
								$gui->showElement($cor,'company');
								$gui->showElement($cor,'website');
								$gui->showElement($cor,'description');
								$gui->showElement($cor,'year_start');
								$gui->showElement($cor,'year_end');
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
			<h4>Add new company</h4>
			<?php
				$gui->inputText('company');
				$gui->inputText('website');
				$gui->inputText('description');
				$gui->inputText('year_start');
				$gui->inputText('year_end');
			?>
			<input type="submit" value="Inserir">
		</form>

	</body>
</html>





