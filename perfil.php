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

					<div class="nombre">
							<?php
								echo "<h1>".$_SESSION['nombre']."</h1>";
								
							?>
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
	                				$usuario = $_SESSION['usuario'];
	                				var_dump($_SESSION['usuario']);
									$correo= $usuario->correo();
	                		?>
	                		<div class="grupo-control">
								<?php
									echo "<h3>Correo:</h3>".$correo;
								?>
							</div>
							<div class= "grupo-control">
								<?php  
									if($usuario->rol()=== 'deportista'){
										$edad = $usuario->edad();
										echo "<h3>Edad:</h3>".$edad;
										?>
										<div class= "grupo-control">
											<?php 
										
												$idPulsera = $_SESSION['idPulsera'];
												echo "<h3>Id de tu pulsera:</h3>".$idPulsera;
											?>
										</div>
										<?php
									}
									else if($usuario->rol()=== 'entrenador'){
										$nClientes = $usuario->nClientes();
										echo "<h3>Numero de clientes:</h3>".$nClientes;
									}
									
								?>
							</div>

						</div>
					</div>
					
					
					<div id="entrenamiento">
						<h3>Entrenamiento</h3>
							<a href="entrenamiento.php">Subir nuevo entrenamiento</a>

					</div>


					<?php
						if(isset($_SESSION['esEntrenador'])&& $_SESSION['esEntrenador']){
							

						}
						else{
							$entrenamiento = $usuario->sesiones($idPulsera);
							if($entrenamiento){
								echo $entrenamiento;
							}
							else echo "Introduce tu primer entrenamiento!";
						}
						
					?>

				</div>

			</div>

		<?php require("includes/comun/pie.php"); ?>
		</body>
	</html>