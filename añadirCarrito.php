<?php
require_once 'menu.php';
require_once 'conecta.php';

// Si no eres cliente, te lleva al login
if ($rol !== 'cliente') {
    header('Location: login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        // Verificar si la cesta existe hoy para el usuario
        $fechaHoy = new DateTime();
        $fechaFormateada = $fechaHoy->format("Y-m-d");

        $queryVerificarCesta = "SELECT cestaID FROM cesta WHERE email = ? AND fechaCreacion = ?";
        $stmtVerificarCesta = $con->prepare($queryVerificarCesta);
        $stmtVerificarCesta->bindParam(1, $email, PDO::PARAM_STR);
        $stmtVerificarCesta->bindParam(2, $fechaFormateada, PDO::PARAM_STR);
        $stmtVerificarCesta->execute();

        // Obtener el id de la cesta (si existe)
        $idCesta = $stmtVerificarCesta->fetchColumn();

        if (!$idCesta) {
            // La cesta no existe, crear una nueva
            $queryCrearCesta = "INSERT INTO cesta (email, fechaCreacion) VALUES (?, ?)";
            
            $stmtCrearCesta = $con->prepare($queryCrearCesta);
            $stmtCrearCesta->bindParam(1, $email, PDO::PARAM_STR);
            $stmtCrearCesta->bindParam(2, $fechaFormateada, PDO::PARAM_STR);
            $stmtCrearCesta->execute();

            // Obtener el id de la cesta creada
            $idCesta = $con->lastInsertId(); // Obtener el último ID insertado
        }

        // Proceder con la inserción en itemcesta
        $productoID = $_POST['id'];
        $cantidad = 1;

        // Verificar si el producto ya existe en la cesta
        $consultaExistencia = "SELECT COUNT(*) AS count, cantidad FROM itemcesta WHERE cestaID = ? AND productoID = ?";
        $stmtExistencia = $con->prepare($consultaExistencia);
        $stmtExistencia->bindParam(1, $idCesta, PDO::PARAM_INT);
        $stmtExistencia->bindParam(2, $productoID, PDO::PARAM_INT);
        $stmtExistencia->execute();

        $resultExistencia = $stmtExistencia->fetch(PDO::FETCH_ASSOC);
        $existencia = $resultExistencia['count'];

        if ($existencia > 0) {
            // El producto ya existe en la cesta, actualiza la cantidad
            $nuevaCantidad = $resultExistencia['cantidad'] + 1;

            // Actualiza la cantidad del producto existente en la cesta
            $actualizarCantidad = "UPDATE itemcesta SET cantidad = ? WHERE cestaID = ? AND productoID = ?";
            $stmtActualizar = $con->prepare($actualizarCantidad);
            $stmtActualizar->bindParam(1, $nuevaCantidad, PDO::PARAM_INT);
            $stmtActualizar->bindParam(2, $idCesta, PDO::PARAM_INT);
            $stmtActualizar->bindParam(3, $productoID, PDO::PARAM_INT);
            $stmtActualizar->execute();
        } else {
            // El producto no existe en la cesta, agrégalo
            $queryIntroduceItemCesta = "INSERT INTO itemcesta (cestaID, productoID, cantidad) VALUES (?, ?, ?)";
            $stmtIntroduceItemCesta = $con->prepare($queryIntroduceItemCesta);
            $stmtIntroduceItemCesta->bindParam(1, $idCesta, PDO::PARAM_INT);
            $stmtIntroduceItemCesta->bindParam(2, $productoID, PDO::PARAM_INT);
            $stmtIntroduceItemCesta->bindParam(3, $cantidad, PDO::PARAM_INT);
            $stmtIntroduceItemCesta->execute();
        }

        // Redirigir a mostrarCesta.php
        header('Location: mostrarCesta.php');
        exit(); // Asegúrate de salir después de redirigir

    } catch (Exception $e) {
        echo "Error al agregar el producto al carrito: " . $e->getMessage();
    }

} else {
    header('Location: login.php');
}
?>

