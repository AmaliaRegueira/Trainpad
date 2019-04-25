<?php

//Inicio del procesamiento

require_once("includes/config.php");
require_once("includes/formularioLoginE.php");

?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/estilo.css" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Iniciar Sesión | Asteyo</title>
</head>

<body>

	<div id="contenedor">

		<?php

			require("includes/comun/cabecera.php");

		?>


			<div class="principal">

				<div id="contenido">
					

					<?php
						$formulario = new formularioLoginE("loginEntrenador", array( 'action' => 'entrenadorLogin.php'));
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