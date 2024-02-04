<?php
include_once 'menu.php';

$rol = isset($_SESSION['tipoUsuario']) ? $_SESSION['tipoUsuario'] : '';

// Verifica si el usuario está logueado y no es un administrador
if ((!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true  && $rol !== 'administrador')) {
    // Si no está logado y no es administrador
    header('Location: menu.php');
    exit();
}

require_once 'conecta.php';

// Función para obtener la lista de productos desde la base de datos
function obtenerProductos($conexion) {
    $sql = "SELECT * FROM productos ORDER BY productoID";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();

    // Usar fetch para obtener resultados uno a uno
    while ($producto = $stmt->fetch(PDO::FETCH_ASSOC)) {
        yield $producto;
    }
}

// Función para obtener la lista de pedidos desde la base de datos
function obtenerPedidos($conexion) {
    $sql = "SELECT * FROM pedidos ORDER BY pedidoID";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();

    // Usar fetch para obtener resultados uno a uno
    while ($pedido = $stmt->fetch(PDO::FETCH_ASSOC)) {
        yield $pedido;
    }
}

// Obtener la lista de productos y pedidos
$productos = obtenerProductos($con);
$pedidos = obtenerPedidos($con);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .btn-accion {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-crear {
            background-color: #4CAF50;
            color: white;
        }

        .btn-actualizar {
            background-color: #357EDD;
            color: white;
        }

        .btn-eliminar {
            background-color: #FF3030;
            color: white;
        }

        img{
            width: 65px;
            height: 100px;
        }
        </style>
    <title>Panel de Administración - Productos y Pedidos</title>
</head>

<body>

    <h2>Panel de Administración - Productos</h2>

    <!-- Botón para crear un nuevo producto -->
    <form action="crear_producto.php" method="post">
        <a href="crear_producto.php"><button class="btn-accion btn-crear">Crear Nuevo Producto</button></a>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto) : ?>
                <tr>
                    <td><?= $producto['productoID']; ?></td>
                    <td><img src="<?= $producto['imagen']; ?>" alt="Imagen del producto"></td>
                    <td><?= $producto['nombre']; ?></td>
                    <td><?= $producto['descripcion']; ?></td>
                    <td><?= $producto['precio']; ?></td>
                    <td>
                        <!-- Botones de acciones (actualizar, eliminar) -->
                        <form action="actualizar_producto.php?id=<?php echo $producto['productoID']; ?>" method="post" style="display: inline;">
                            <input type="hidden" name="id" value="<?= $producto['productoID']; ?>">
                            <button type="submit" class="btn-accion btn-actualizar">Actualizar</button>
                        </form>
                        <form action="eliminar_producto.php" method="post" onsubmit="return confirm('¿Estás seguro de que deseas borrar este producto?');" style="display: inline;">
                            <input type="hidden" name="id" value="<?= $producto['productoID']; ?>">
                            <button type="submit" class="btn-accion btn-eliminar">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

   <!-- Tabla de Pedidos -->
   <h3>Lista de Pedidos</h3>
    <table>
        <thead>
            <tr>
                <th>ID del Pedido</th>
                <th>Fecha del Pedido</th>
                <th>Entregado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pedidos as $pedido) : ?>
                <tr>
                    <td><?= $pedido['pedidoID']; ?></td>
                    <td><?= $pedido['fechaPedido']; ?></td>
                    <td><?= ($pedido['entregado'] == 1) ? 'Sí' : 'No'; ?></td>

                    <td>
                        <!-- Botones de acciones para modificar el estado del pedido -->
                        <form action="modificar_estado_pedido.php?id=<?php echo $pedido['pedidoID']; ?>" method="post" style="display: inline;">
                            <input type="hidden" name="id" value="<?= $pedido['pedidoID']; ?>">
                            <button type="submit" class="btn-accion btn-actualizar">Modificar Estado</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php
    // Cerrar la conexión
    require_once 'desconecta.php';
    ?>

</body>

</html>