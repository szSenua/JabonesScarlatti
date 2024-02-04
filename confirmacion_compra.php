<?php

require_once 'menu.php';

date_default_timezone_set('Europe/Madrid');

// Verifica si el pedidoID y la fecha de entrega están presentes en la URL
if (isset($_GET['pedidoID']) && isset($_GET['fechaEntrega'])) {
    $pedidoID = $_GET['pedidoID'];
    $fechaEntregaEstimada = urldecode($_GET['fechaEntrega']);

    // Formatear la fecha
    $timestamp = strtotime($fechaEntregaEstimada);
    setlocale(LC_TIME, 'es-ES'); //es-ES porque es windows, en Linux es_ES
    $fechaFormateada = strftime('%e de %B de %Y', $timestamp);

    echo "<div class='exito-container'>";
    echo '<i class="ri-checkbox-fill"></i>';
    echo "<h2>¡Compra exitosa!</h2>";
    echo "<p>Gracias por tu compra. Tu pedido con ID $pedidoID ha sido procesado correctamente.</p>";
    echo "<p>La fecha de entrega estimada es el $fechaFormateada.</p>";
    echo "</div>";
} 
?>

