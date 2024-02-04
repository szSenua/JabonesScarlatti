<?php
require_once 'conecta.php';

$errores = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoge los datos del formulario
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $direccion = isset($_POST['direccion']) ? trim($_POST['direccion']) : '';
    $cp = isset($_POST['cp']) ? trim($_POST['cp']) : '';
    $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';

    // Validaciones
    if (!empty($email)) {
        // Validar el formato del correo electrónico
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El formato del correo electrónico no es válido.';
        }
    } else {
        $errores[] = 'El campo correo electrónico no puede estar vacío.';
    }

    if (empty($nombre)) {
        $errores[] = 'El campo nombre no puede estar vacío.';
    }

    if (empty($direccion)) {
        $errores[] = 'El campo dirección no puede estar vacío.';
    }

    if (empty($cp)) {
        $errores[] = 'El campo código postal no puede estar vacío.';
    }

    // Validar teléfono
    if (!empty($telefono) && !preg_match("/^[0-9]{9}$/", $telefono)) {
        $errores[] = 'El formato del teléfono no es válido.';
    } elseif (empty($telefono)) {
        $errores[] = 'El campo teléfono no puede estar vacío.';
    }

    // Si no hay errores, realiza la inserción en la base de datos
    if (empty($errores)) {
        try {
            // Prepara la consulta SQL
            $sql = "INSERT INTO clientes (email, nombre, direccion, cp, telefono) VALUES (:email, :nombre, :direccion, :cp, :telefono)";
            $stmt = $con->prepare($sql);

            // Bind de parámetros
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
            $stmt->bindParam(':cp', $cp, PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);

            // Ejecuta la consulta
            $stmt->execute();

            // Redirige al login
            if ($stmt->rowCount() > 0) {
                header('Location: login.php'); 
                exit();
            } else {
                $errores[] = 'Error al registrar el cliente. Por favor, inténtalo de nuevo.';
            }
        } catch (PDOException $e) {
            $errores[] = 'Error de base de datos: ' . $e->getMessage();
        }
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
    <title>Registro</title>
    <style>
            body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            max-width: 400px;
            width: 100%;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
            font-weight: bolder;
        }

        input[type="submit"] {
            background-color: #342042;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #342042;
        }

        p {
            color: red;
        }
        a{
            display: flex;
            align-items: center;
            justify-content: center;
        }
    
    </style>
</head>
<body>
   
    <form action="" method="POST">
    <h2>Registro</h2>
        <?php
        // Muestra los errores, si los hay
        if (!empty($errores)) {
            foreach ($errores as $error) {
                echo '<p style="color: red;">' . htmlspecialchars($error) . '</p>';
            }
        }
        ?>

        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" value="<?php echo htmlspecialchars($nombre ?? ''); ?>" >

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" >

        <label for="direccion">Dirección:</label>
        <input type="text" name="direccion" value="<?php echo htmlspecialchars($direccion ?? ''); ?>" >

        <label for="cp">Código Postal:</label>
        <input type="text" name="cp" value="<?php echo htmlspecialchars($cp ?? ''); ?>" >
        <label for="telefono">Teléfono:</label>
        <input type="text" name="telefono" value="<?php echo htmlspecialchars($telefono ?? ''); ?>" >

        <input type="submit" value="Registrar" class="submit">
        <a href="login.php">¿Ya tienes una cuenta?</a>
    </form>
    
</body>
</html>
