<?php

require '../../classes/Database.php';
require '../../classes/Validator.php';
require '../../classes/ErrorHandler.php';
require '../../classes/AdminGui.php';
require '../../functions/security.php';

$errorHandler = new ErrorHandler;

$db = new Database;
$gui = new AdminGui($db);

$media_types_records = $gui->select('media_types');

if(!empty($_POST)){
	$db->table('media_types');
	if($db->delete($_POST['id'])){
		header('Location: delete_media_types.php');
		die();
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Delete media types</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body style="font-family:Helvetica;font-size:12px">
		<h3>Media types</h3>
		<h4>Delete media type</h4>

		<?php
		if(!count($media_types_records)){
			echo 'Tabela vazia';
		}
		else{
		?>

			<table>
				<thead>
					<tr>
						<th class="id_field">id</th>
						<th>media_type</th>
					</tr>
				</thead>
				<tbody>

					<?php
					foreach($media_types_records as $mtr){
					?>
						<tr>
							<?php
								$gui->showElement($mtr,'id');
								$gui->showElement($mtr,'media_type');
								$gui->deleteButton($mtr);
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






