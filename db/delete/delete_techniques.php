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
	if($db->delete($_POST['id'])){
		$db->table('techniques_for_projects');
		$db->deleteWhich($_POST['id'],'technique_id');
		$db->table('techniques_for_works');
		$db->deleteWhich($_POST['id'],'technique_id');
		header('Location: delete_techniques.php');
		die();
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Delete techniques</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body style="font-family:Helvetica;font-size:12px">
		<h3>Techniques</h3>
		<h4>Delete technique</h4>

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
								$gui->deleteButton($tr);
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






