<?php

	require_once("includes/config.php");
	require_once("includes/usuario.php");
	require_once("includes/entrenador.php");

?>
<!DOCTYPE html>
	<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/estilo.css" />
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
			<title>Perfil</title>
		</head>
		<body>
			<?php

			require("includes/comun/cabecera.php");

		?>

			<div class="principal">

				<div id="contenido">	
					<div class= "entrenadores">
						<h1>Seleccione un entrenador y escribale un correo con el id de su pulsera.</h1>
						<?php

							$imprimir = Entrenador::imprimeEntrenadores();
							echo $imprimir;
						?>
					</div>

				</div>

			</div>

		</body>
	</html>