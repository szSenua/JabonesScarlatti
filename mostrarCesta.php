<?php
require_once 'menu.php';
require_once 'conecta.php';

// Si no eres cliente, te lleva al login
if ($rol !== 'cliente') {
    header('Location: login.php');
}

try {
    // Obtener el id de la última cesta del usuario
    $queryObtenerIDCesta = "SELECT cestaID FROM cesta WHERE email = ? ORDER BY fechaCreacion DESC LIMIT 1";
    $stmtObtenerIDCesta = $con->prepare($queryObtenerIDCesta);
    $stmtObtenerIDCesta->bindParam(1, $email, PDO::PARAM_STR);
    $stmtObtenerIDCesta->execute();

    // Obtener el id de la cesta
    $idCesta = $stmtObtenerIDCesta->fetchColumn();

    // Obtener los detalles de los productos en la cesta
    $queryObtenerProductosCesta = "SELECT p.*, ic.cantidad FROM productos p
                                    JOIN itemcesta ic ON p.productoID = ic.productoID
                                    WHERE ic.cestaID = ?";
    $stmtObtenerProductosCesta = $con->prepare($queryObtenerProductosCesta);
    $stmtObtenerProductosCesta->bindParam(1, $idCesta, PDO::PARAM_INT);
    $stmtObtenerProductosCesta->execute();

    // Verificar si la cesta está vacía
    $rowCount = $stmtObtenerProductosCesta->rowCount();

    // Inicializar la variable para el total
    $totalCompra = 0;

    // Guardar la información de la cesta en la sesión
    $_SESSION['cesta'] = [
        'idCesta' => $idCesta,
        'productos' => [],
        'totalCompra' => $totalCompra,
    ];

    while ($fila = $stmtObtenerProductosCesta->fetch(PDO::FETCH_ASSOC)) {
        // Agregar productos a la cesta en la sesión
        $_SESSION['cesta']['productos'][] = [
            'productoID' => $fila['productoID'],
            'nombre' => $fila['nombre'],
            'descripcion' => $fila['descripcion'],
            'cantidad' => $fila['cantidad'],
            'precio' => $fila['precio'],
        ];

        // Sumar al total de la compra
        $totalCompra += $fila['precio'] * $fila['cantidad'];
    }

    // Actualizar el total de la compra en la sesión
    $_SESSION['cesta']['totalCompra'] = $totalCompra;

} catch (Exception $e) {
    echo "Error al mostrar la cesta: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compra</title>
    <style>
        .cesta-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        table {
            width: 65%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: center;
            background: white;
        }

        th {
            background-color: #f2f2f2;
        }

        th:nth-child(3),
        td:nth-child(3) {
            width: 20%;
        }

        .btn-accion {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        img {
            height: 100px;
            width: 65px;
        }

        .styled-input {
            width: 50px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }

        .btn-rechazar {
            background-color: #FF3030;
            color: white;
            margin-right: .3em;
        }

        .btn-adjudicar {
            background-color: #357EDD;
            color: white;
        }

        .btn-crear {
            background-color: #4CAF50;
            color: white;
        }

        .mensaje-vacio {
            font-weight: bolder;
            margin-top: 20px;
            text-align: center;
            font-size: 18px;
            color: #555;
        }
        </style>
</head>
<body>

<h1>Carrito de la compra</h1>
<div class="cesta-container">
    <?php
    if ($rowCount > 0) {
        $stmtObtenerProductosCesta->execute();

        echo '<table>';
        echo '<tr>
                <th>Imagen</th>
                <th>Producto</th>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Acción</th>
              </tr>';

        while ($fila = $stmtObtenerProductosCesta->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td><img src="' . $fila['imagen'] . '"></td>';
            echo '<td>' . $fila['nombre'] . '</td>';
            echo '<td>' . $fila['descripcion'] . '</td>';
            echo '<form method="post" action="gestionarCesta.php">';
            echo '<td><input type="number" name="cantidad" value="' . $fila['cantidad'] . '" min="1" max="2" class="styled-input"></td>';
            $precioTotal = $fila['precio'] * $fila['cantidad'];
            echo '<td>' . $precioTotal . ' €</td>';
            echo '<input type="hidden" name="id" value="' . $fila['productoID'] . '">';
            echo '<input type="hidden" name="cestaID" value="' . $idCesta . '">';
            echo "<td><button class='btn-accion btn-rechazar' type='submit' name='quitar'>Quitar</button>";
            echo "<button class='btn-accion btn-adjudicar' type='submit' name='actualizar'>Actualizar</button></td>";
            echo '</form>';
            echo '</tr>';
        }

        // Mostrar el total de la compra dentro de la tabla
        echo '<tr>
                <td colspan="4"><b>Total<b></td>
                <td>' . $totalCompra . ' €</td>
                <td><form method="post" action="procesar_compra.php">
                <input type="hidden" name="cestaID" value="' . $idCesta . '">
                <button class="btn-accion btn-crear" type="submit" name="comprar">Realizar Compra</button>
                </form>
                </td>
              </tr>';
        echo '</table>';
    } else {
        echo '<div class="mensaje-vacio">La cesta está vacía.</div>';
    }
    ?>
</div>
</body>
</html>

<?php
// Cerrar la conexión
require_once 'desconecta.php';
?>