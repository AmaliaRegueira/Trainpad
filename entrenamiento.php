<?php

//Inicio del procesamiento

require_once("includes/config.php");
require_once("includes/formularioEntrenamiento.php");

?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/estilo.css" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Entrenamiento</title>
</head>

<body>

	<div id="contenedor">

		<?php

			require("includes/comun/cabecera.php");

		?>


			<div class="principal">

				<div id="contenido">
					<!--<div id="formEntrenamiento">-->

						<?php
							$formulario = new formularioEntrenamiento("entrenamiento", array( 'action' => 'entrenamiento.php'));
							$formulario->gestiona();
						?>

					<!--</div>-->
				</div>
			</div>

		<?php

			require("includes/comun/pie.php");

		?>


	</div>

</body>
</html>