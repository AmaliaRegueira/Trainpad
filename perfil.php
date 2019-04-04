<?php

	require_once("includes/config.php");
	require_once("includes/usuario.php");

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

					<div id="edit">
						<a href='editarPerfil.php' id='edit'>Editar</a>
					</div>					
					
					<div id="panel-perfil">
						<div id="foto">
						<?php
							$imgPerfil = "mysql/img/".$_SESSION["nombre"]."/fotoPerfil.jpg";
							echo '<img id="img-perfil" src='.$imgPerfil.'>';
						?>

	                	</div>
	                	<div id="perfil">
							<?php
								$usuario = Usuario::BuscaUsuario($_SESSION["idPulsera"]);
								//echo "Nombre: ".$_SESSION["nombre"];

								echo "Nombre:<p> ".$usuario->nombre()."</p>";
								

							?>
						</div>
					</div>
					
					
					<div id="entrenamiento">
						<h3>Entrenamiento</h3>
							<a href="entrenamiento.php">Subir nuevo entrenamiento</a>

					</div>
				</div>

			</div>

		<?php require("includes/comun/pie.php"); ?>
		</body>
	</html>