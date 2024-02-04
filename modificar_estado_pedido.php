<?php
// modificar_pedido.php

require_once 'conecta.php';  

// Verifica si se recibió el id del pedido
if (isset($_POST['id'])) {
    $pedidoID = $_POST['id'];

    // Realiza la lógica para actualizar el estado en la base de datos
    global $con;

    // Verifica el estado actual del pedido
    $query = "SELECT Entregado FROM pedidos WHERE pedidoID = ?";
    $stmt = $con->prepare($query);
    $stmt->bindParam(1, $pedidoID, PDO::PARAM_INT);
    $stmt->execute();

    $estadoActual = $stmt->fetchColumn();

    // Calcula el nuevo estado (0 -> 1, 1 -> 0)
    $nuevoEstado = ($estadoActual == 0) ? 1 : 0;

    // Actualiza el estado del pedido en la base de datos
    $updateQuery = "UPDATE pedidos SET Entregado = ? WHERE pedidoID = ?";
    $updateStmt = $con->prepare($updateQuery);
    $updateStmt->bindParam(1, $nuevoEstado, PDO::PARAM_INT);
    $updateStmt->bindParam(2, $pedidoID, PDO::PARAM_INT);
    $updateStmt->execute();

    require_once 'desconecta.php';
    // Redirige de nuevo a la página de pedidos
    header('Location: panel_administracion.php');
    exit();
} 


?>
