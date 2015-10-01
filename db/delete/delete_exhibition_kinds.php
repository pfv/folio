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
	if($db->delete($_POST['id'])){
		header('Location: delete_exhibition_kinds.php');
		die();
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Delete exhibition kinds</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body style="font-family:Helvetica;font-size:12px">
		<h3>Exhibition kinds</h3>
		<h4>Delete exhibition kind</h4>

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
								$gui->deleteButton($ekr);
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






