<div class="cabecera">

	<!--<div id="logo">
		<a href="index.php"><img src="img/logoasteyonombre.png" /></a>
	</div>-->
	<div id="titulo">
		
			<h1> Trainpad </h1>
	</div>
	<div id="link">
		<?php
			if (isset($_SESSION["login"]) && ($_SESSION["login"]===true)) {
				echo "<p>Bienvenido, " . $_SESSION['nombre'] . ".<p>" .
				"<a href='perfil.php' class='perfil'>Perfil</a>
				<a href='logout.php' class='salir'>Salir</a>";		
			} else {
				echo "<a href='login.php' class='login'>Iniciar sesión</a> 
				<a href='registro.php' class='registro'>Registrarse</a>";
			}
		?>
	</div>
</div>
