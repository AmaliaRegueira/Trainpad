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

					<div id="edit">
						<a href='editarPerfil.php' id='edit'>Editar</a>
					</div>	

					<div class="nombre">
							<?php
								if(isset($_SESSION['esEntrenador'])&& $_SESSION['esEntrenador']){
									$usuario = Entrenador::searchTrainer($_SESSION['nombre']);

								}
								else{
									$usuario = Usuario::buscaUsuario($_SESSION['id']);
								}
								echo "<h1>".$_SESSION['nombre']."</h1>";
								
							?>
					</div>
					<div id="panel-perfil">
						
						
	                	<div id="perfil">
	                		
	                		<div class="grupo-control">
								<?php
									$correo = $usuario->email();
									echo "<h3>Correo:</h3>".$correo;

								?>
							</div>
							
								<?php  

									if(isset($_SESSION['esEntrenador']) && $_SESSION['esEntrenador']){
										?>
										<div class= "grupo-control">
										<?php
											$nClientes = $usuario->nClientes($usuario);
											echo "<h3>Número de clientes:<h3>".$nClientes;
										?>
										</div>
										<?php	
									}
									else {
								?>
										<div class= "grupo-control">
								<?php
										$edad = $usuario->edad();
										echo "<h3>Edad:</h3>".$edad;
								?>
										</div>
							
										<div class= "grupo-control">
											<?php 
										
												$idPulsera = $_SESSION['id'];
												echo "<h3>Id de tu pulsera:</h3>".$idPulsera;
											?>
										</div>
										<div class= "grupo-control">
											<?php

											$entrenador = $usuario->entrenador();
											if(!$entrenador) $entrenador ="<a href='entrenadores.php'> Escoge un entrenador.</a>";
												echo "<h3>Entrenador:</h3>".$entrenador;
											?>
										</div>
										<?php
									}
									
											?>
						</div>

					</div>
					
				<?php
						if(isset($_SESSION['esEntrenador'])){
							?>
							<div id="entrenamiento">
								<h3>Clientes</h3>
								<a href="nuevoCliente.php">Añadir nuevo cliente.</a>
							<?php
								echo $usuario->clientes($usuario);
							?>
							</div>
							<?php
						}
						else{
							?>
							<div id="entrenamiento">
								<h3>Entrenamiento</h3>
								<a href="entrenamiento.php">Subir nuevo entrenamiento</a>

							</div>
							<?php
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