<?php
session_start();

$rol = isset($_SESSION['tipoUsuario']) ? $_SESSION['tipoUsuario'] : '';

if ((!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true  && $rol !== 'administrador')) {
    // Si no está logado y no es admin
    header('Location: menu.php');
    exit(); 
}

include_once 'conecta.php';

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    // Obtener el ID del producto a eliminar
    $idProducto = $_POST['id'];

    // Eliminar el producto de la base de datos
    $sql = "DELETE FROM productos WHERE productoID = :id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':id', $idProducto, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: panel_administracion.php");
    } else {
        echo '<p style="color: red;">Error al eliminar el producto.</p>';
        echo '<p style="color: red;">' . $stmt->errorInfo()[2] . '</p>';
    }
}

// Cerrar la conexión
require_once 'desconecta.php';
?>
