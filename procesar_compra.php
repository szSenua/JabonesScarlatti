<?php
require_once 'menu.php';
require_once 'conecta.php';
require_once 'funciones.php';
require_once __DIR__ . '/fpdf/fpdf.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comprar'])) {

//Consulto la cantidad de itemPedidos que haya tenido el cliente en los 30 días anteriores a la fecha actual

$fechaHoy = new DateTime();
$fechaFormateada = $fechaHoy->format("Y-m-d");

$cantidadItemPedidos = "SELECT IFNULL(SUM(ip.unidades), 0) AS totalItems FROM pedidos p
                        JOIN itempedido ip ON p.pedidoID = ip.pedidoID
                        WHERE p.email = ? AND p.fechaPedido >= DATE_SUB(?, INTERVAL 30 DAY)";

$stmtCantidadItemPedidos = $con->prepare($cantidadItemPedidos);
$stmtCantidadItemPedidos->bindParam(1, $email, PDO::PARAM_STR);
$stmtCantidadItemPedidos->bindParam(2, $fechaFormateada, PDO::PARAM_STR);
$stmtCantidadItemPedidos->execute();

$resultCantidadItems = $stmtCantidadItemPedidos->fetch(PDO::FETCH_ASSOC);

//Obtengo la cantidad total de items de pedidos
$totalItems = $resultCantidadItems['totalItems'];


//Consulto la cantidad de itemCesta de la cesta con el id que le paso

$cestaID = $_POST['cestaID'];
//var_dump("cestaID: " . $cestaID);

//Si SUM(ic.cantidad) no existe daría un 0, devuelve siempre un resultado
$cantidadItemCesta = "SELECT IFNULL(SUM(ic.cantidad), 0) AS totalItemsCesta 
                      FROM cesta c
                      LEFT JOIN itemcesta ic ON c.cestaID = ic.cestaID 
                      WHERE c.cestaID = ?";
      
//$cantidadItemCesta = "SELECT SUM(ic.cantidad) AS totalItemsCesta 
  //          FROM cesta c
    //        LEFT JOIN itemcesta ic ON c.cestaID = ic.cestaID 
      //      WHERE c.cestaID = ?";

$stmtCantidadItemCesta = $con->prepare($cantidadItemCesta);
$stmtCantidadItemCesta->bindParam(1, $cestaID, PDO::PARAM_INT);
$stmtCantidadItemCesta->execute();

$resultCantidadItemsCesta = $stmtCantidadItemCesta->fetch(PDO::FETCH_ASSOC);

//Obtengo la cantidad total de items de la cesta
$totalItemsCesta = $resultCantidadItemsCesta['totalItemsCesta'];

var_dump("Total items cesta: ".$totalItemsCesta);
var_dump("Total items pedido: " . $totalItems);

$realizaCompra = false;

if($totalItems >= 2){
    $realizaCompra = false;
}

if($totalItems == 1 && $totalItemsCesta == 1){
    $realizaCompra = true;

} 

if($totalItems == 1 && $totalItemsCesta > 1){
    $realizaCompra = false;

} 

if($totalItems == 0 && $totalItemsCesta > 2){
    $realizaCompra = false;
}

if($totalItems == 0 && $totalItemsCesta == 2){
    $realizaCompra = true;
}

if($totalItems == 0 && $totalItemsCesta == 1){
    $realizaCompra = true;

}

// Si existe éxito en la compra, hago el insert
if ($realizaCompra) {
    // Registrar el pedido en la tabla pedidos
    $fechaPedido = (new DateTime())->format('Y-m-d');
    $fechaEntrega = (new DateTime($fechaPedido))->modify('+7 days')->format('Y-m-d');

    // Calcular el total de la compra
    $totalCompra = 0;

    $insertPedido = "INSERT INTO pedidos (email, fechaPedido, fechaEntrega, totalPedido)
                     VALUES (?, ?, ?, ?)";

    $stmtInsertPedido = $con->prepare($insertPedido);
    $stmtInsertPedido->bindParam(1, $email, PDO::PARAM_STR);
    $stmtInsertPedido->bindParam(2, $fechaPedido, PDO::PARAM_STR);
    $stmtInsertPedido->bindParam(3, $fechaEntrega, PDO::PARAM_STR);
    $stmtInsertPedido->bindParam(4, $totalCompra, PDO::PARAM_STR);

    if ($stmtInsertPedido->execute()) {
        $pedidoID = $con->lastInsertId();

        // Calcular el total de la compra y registrar los productos en la tabla itempedido
        foreach ($_SESSION['cesta']['productos'] as $producto) {
            $productoID = $producto['productoID'];
            $unidades = $producto['cantidad'];
            $precio = $producto['precio']; 
            $totalCompra += $precio * $unidades;

            // Registrar el producto en itempedido
            $insertItemPedido = "INSERT INTO itempedido (pedidoID, productoID, unidades)
                                VALUES (?, ?, ?)";

            $stmtInsertItemPedido = $con->prepare($insertItemPedido);
            $stmtInsertItemPedido->bindParam(1, $pedidoID, PDO::PARAM_INT);
            $stmtInsertItemPedido->bindParam(2, $productoID, PDO::PARAM_INT);
            $stmtInsertItemPedido->bindParam(3, $unidades, PDO::PARAM_INT);
            $stmtInsertItemPedido->execute();
        }

        // Actualizar el total de la compra en el pedido
        $updateTotalPedido = "UPDATE pedidos SET totalPedido = ? WHERE pedidoID = ?";
        $stmtUpdateTotalPedido = $con->prepare($updateTotalPedido);
        $stmtUpdateTotalPedido->bindParam(1, $totalCompra, PDO::PARAM_INT);
        $stmtUpdateTotalPedido->bindParam(2, $pedidoID, PDO::PARAM_INT);
        $stmtUpdateTotalPedido->execute();

        // Borrar la cesta
        $idCesta = $_SESSION['cesta']['idCesta'];
        $borrarCesta = "DELETE FROM cesta WHERE cestaID = ?";
        $stmtBorrarCesta = $con->prepare($borrarCesta);
        $stmtBorrarCesta->bindParam(1, $idCesta, PDO::PARAM_INT);
        $stmtBorrarCesta->execute();

   

        //Generar Albarán
        $pdfNombre = generarNombrePDF($email, $fechaPedido);
        generarAlbaranPDF($pedidoID);

        // Limpiar la cesta en la sesión después de completar la compra
        unset($_SESSION['cesta']);
    }
}
}

// Cerrar la conexión
require_once 'desconecta.php';
?>