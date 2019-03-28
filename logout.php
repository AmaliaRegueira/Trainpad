<?php

//Inicio del procesamiento
require_once("includes/config.php");

//Doble seguridad: unset + destroy
unset($_SESSION["login"]);
unset($_SESSION["esAdmin"]);
unset($_SESSION["nombre"]);


session_destroy();


header("Location: ../index.php");
