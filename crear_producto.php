<?php
require_once 'menu.php';
$rol = isset($_SESSION['tipoUsuario']) ? $_SESSION['tipoUsuario'] : '';

if ((!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true  && $rol !== 'administrador')) {
    // Si no está logado y no es admin
    header('Location: menu.php');
    exit(); 
}

include_once 'conecta.php';

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    // Recoger los datos del formulario
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';
    $peso = isset($_POST['peso']) ? $_POST['peso'] : '';
    $precio = isset($_POST['precio']) ? $_POST['precio'] : '';

    // Verificar si se ha subido una imagen
    $imagen = '';
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $nombreImagen = $_FILES['imagen']['name'];
    $rutaImagen = 'imagenes/' . $nombreImagen;
    move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaImagen);
    $imagen = $rutaImagen;
}

    // Insertar los datos en la base de datos
    $sql = "INSERT INTO productos (nombre, descripcion, peso, precio, imagen) VALUES (:nombre, :descripcion, :peso, :precio, :imagen)";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
    $stmt->bindParam(':peso', $peso, PDO::PARAM_STR);
    $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
    $stmt->bindParam(':imagen', $imagen, PDO::PARAM_STR);

    if ($stmt->execute()) {
        header("Location: panel_administracion.php");
    } else {
        echo '<p style="color: red;">Error al crear el producto.</p>';
        echo '<p style="color: red;">' . $stmt->errorInfo()[2] . '</p>';
    }
}

// Cerrar la conexión
require_once 'desconecta.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input,
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
    <title>Crear Producto</title>
</head>

<body>
    <div class="container">
        <h2>Crear Nuevo Producto</h2>
        <form action="crear_producto.php" method="post" enctype="multipart/form-data">

            <label for="nombre">Nombre del Producto:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="descripcion">Descripción del Producto:</label>
            <textarea id="descripcion" name="descripcion" required></textarea>

            <label for="peso">Peso del Producto:</label>
            <input type="text" id="peso" name="peso" required>

            <label for="precio">Precio del Producto:</label>
            <input type="text" id="precio" name="precio" required>

            <label for="imagen">Imagen del Producto:</label>
            <input type="file" id="imagen" name="imagen" accept="image/*">

            <button type="submit" name="submit">Crear Producto</button>
        </form>
    </div>
</body>

</html>
