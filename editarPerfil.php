<?php

//Inicio del procesamiento
require_once("includes/config.php");
require_once("includes/formularioEdit.php");

?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/estilo.css" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Registro</title>
</head>

<body>

	<div id="contenedor">

		<?php
			require("includes/comun/cabecera.php");
		?>

			<div class="principal">

				<!--<?php require("includes/comun/sidebarIzq.php"); ?>-->

				<div id="contenido">
					<h1>Editar perfil: </h1>
							<?php
								$formulario = new formularioEdit("editar", array( 'action' => 'editarPerfil.php'));
								$formulario->gestiona();
							?>
					
				</div>


			</div>

		<?php
			require("includes/comun/pie.php");
		?>

	</div>

</body>
</html>