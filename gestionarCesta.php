<?php
session_start();
require_once 'conecta.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quitar'])) {

$productoID = $_POST['id'];
$cestaID = $_POST['cestaID'];

$eliminaItem = "DELETE FROM itemcesta WHERE cestaID = ? AND productoID = ?";
$stmt = $con->prepare($eliminaItem);


$stmt->bindParam(1, $cestaID, PDO::PARAM_STR);
$stmt->bindParam(2, $productoID, PDO::PARAM_STR);

$stmt->execute();

require_once 'desconecta.php';

header('Location: mostrarCesta.php');


} else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])){

$productoID = $_POST['id'];
$cestaID = $_POST['cestaID'];
$cantidad = $_POST['cantidad'];

$actualizaCantidad = "UPDATE itemcesta SET cantidad = ? WHERE productoID = ? AND cestaID = ?";

$stmt = $con->prepare($actualizaCantidad);

$stmt->bindParam(1, $cantidad, PDO::PARAM_STR);
$stmt->bindParam(2, $productoID, PDO::PARAM_STR);
$stmt->bindParam(3, $cestaID, PDO::PARAM_STR);

$stmt->execute();

require_once 'desconecta.php';

header('Location: mostrarCesta.php');

} else {
    header('Location: login.php');
}


?>