<?php
include ('../../config.php');

session_start();

// Destruir todas las variables de sesión
session_unset();
session_destroy();

// Redireccionar al inicio de la aplicación
header("Location: " . $URL . "/index.php");
exit();