<?php
require_once 'menu.php';

// Muestra los errores, si los hay
if (isset($_SESSION['errores']) && !empty($_SESSION['errores'])) {
    echo '<div class= "errores" style="color: red; text-align: center;">';
    echo '<i class="ri-spam-2-fill"></i>';
    foreach ($_SESSION['errores'] as $error) {
        echo '<h2>' . htmlspecialchars($error) . '</h2>';
    }
    echo '</div>';

    // Limpia los errores de la sesión
    unset($_SESSION['errores']);
}
?>
