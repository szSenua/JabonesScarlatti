<?php

//Aqui ya tengo la sesión propagada y el rol
require_once 'menu.php';
require_once 'conecta.php';

// Configuración de la paginación
$elementosPorPagina = 4;
$paginaActual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $elementosPorPagina;

$consulta = "SELECT * FROM productos LIMIT :elementosPorPagina OFFSET :offset";
$stmt = $con->prepare($consulta);
$stmt->bindValue(':elementosPorPagina', $elementosPorPagina, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        #jabones-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 2em;
        }

        .titulo{
            font-weight: 500;
        }

        .card {
            text-align:center;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 15px;
            width: 360px;
            background-color: #fff;
        }
        .btn-carrito {
            font-weight: bolder;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            background-color: green;
            color: white;
        }

        .pagination-container {
        text-align: center;
        margin-top: 10px; 
    }

    .pagination a {
        display: inline-block;
        padding: 5px 10px;
        margin: 0 5px;
        text-decoration: none;
        border: 1px solid #ccc;
        border-radius: 3px;
        background-color: #f0f0f0;
        color: #333;
        transition: opacity 0.5s; /* Suavizar el cambio de una página a otra */
    }

    .pagination a:hover {
        background-color: #ddd;
    }
        
    

    #jabones-container.fade-out {
        transform: translateX(-100%); 
        transition: transform 0.3s;
    }

    #jabones-container.fade-out.izquierda {
        transform: translateX(-100%);
        transition: transform 0.3s;
    }

    
    #jabones-container.fade-out.derecha {
        transform: translateX(100%);
        transition: transform 0.3s;
    }
       
    </style>
    <title>Listar Cursos</title>
</head>

<body>
<div id="jabones-container">

<?php

while($fila = $stmt->fetch(PDO::FETCH_ASSOC)){
    echo '<div class="card">';
    echo '<h2>' . $fila['nombre'] . '</h2>';
    echo '<img src=" ' . $fila['imagen'] . '">';
    echo '<p class="titulo">Descripción:<br>' . $fila['descripcion'] . '</p>';
    echo '<p class="titulo">Peso:' . $fila['peso'] . ' kg</p>';
    echo '<p class="titulo">Precio: ' . $fila['precio'] . ' €</p>';

    if ($rol === 'cliente' || $rol === 'administrador') {
        echo '<form action="añadirCarrito.php" method="post">';
        echo '<input type="hidden" name="id" value="' . $fila['productoID'] . '">';
        echo '<button class="btn-carrito">Añadir al carrito</button>';
        echo '</form>';
    
    }

    echo '</div>';
}

?>

</div>

 <!-- Paginación -->
 <div class="pagination-container">
    <div class="pagination">
        <?php
        // Obtener el total de registros
        $totalRegistros = $con->query("SELECT COUNT(*) FROM productos")->fetchColumn();
        $totalPaginas = ceil($totalRegistros / $elementosPorPagina);

        for ($i = 1; $i <= $totalPaginas; $i++) {
            echo '<a href="?pagina=' . $i . '" class="pagination-page">' . $i . '</a>';
        }
        ?>
    </div>
    </div>

    <script>
    // Variable para almacenar la página actual
    var paginaActual = <?php echo $paginaActual; ?>;

    // Función para manejar el clic en los enlaces de paginación
    function handlePaginationClick(event) {
        event.preventDefault();

        // Obtiene el contenedor con el id "jabones-container"
        var jabonesContainer = document.getElementById('jabones-container');

        // Obtiene la URL del enlace
        var nextPageUrl = event.target.href;

        // Obtiene el número de la página a la que intentas ir
        var paginaDestino = parseInt(event.target.textContent);

        // Calcula la dirección del desplazamiento
        var direccion = paginaDestino < paginaActual ? 'derecha' : 'izquierda';

        // Verifica si la página actual es la misma que la del botón clicado
        if (paginaActual !== paginaDestino) {
            // Aplica la clase de transición
            jabonesContainer.classList.add('fade-out', direccion);

            // Espera a que termine la transición antes de redirigir
            setTimeout(function () {
                window.location.href = nextPageUrl;
            }, 300); 
        }
    }

    // Agrega un listener a todos los enlaces de paginación
    var paginationLinks = document.querySelectorAll('.pagination-page');
    paginationLinks.forEach(function (link) {
        link.addEventListener('click', handlePaginationClick);
    });
</script>

</body>

</html>