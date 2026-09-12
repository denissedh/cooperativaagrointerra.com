<?php
session_start();
define('SERVIDOR','localhost');
define('USUARIO','root');
define('PASSWORD','');
define('BD','sistemadeventas');

$servidor = "mysql:dbname=".BD.";host=".SERVIDOR;
try{
    $pdo = new PDO($servidor, USUARIO, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES utf8"));
}catch (PDOException $e){
    die("Error de conexión");
}

// Iniciar carrito
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

date_default_timezone_set('America/Mexico_City');
$fechaHora = date('Y-m-d H:i:s');
?>