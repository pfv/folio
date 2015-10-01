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
	if($db->delete($_POST['id'])){
		$db->table('companies_for_projects');
		$db->deleteWhich($_POST['id'],'company_id');
		header('Location: delete_companies.php');
		die();
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Delete companies</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body style="font-family:Helvetica;font-size:12px">
		<h3>Companies</h3>
		<h4>Delete company</h4>

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
								$gui->deleteButton($cor);
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
	</body>
</html>





