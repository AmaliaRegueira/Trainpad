<?php

//Inicio del procesamiento
require_once("includes/config.php");
require_once("includes/formularioCliente.php");

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

				<div id="contenido">
					<h1>Registro: </h1>
					
					<?php
						$formulario = new formularioCliente("nuevoCliente", array( 'action' => 'nuevoCliente.php'));
						$formulario->gestiona();
					?>
				</div>


			</div>

		

	</div>

</body>
</html>