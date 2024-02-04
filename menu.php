<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
    href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css"
    rel="stylesheet"
/>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            overflow: hidden; 
        }

        nav {
            background-color: #342042;
            overflow: hidden;
            position:sticky;
            top: 0;
            
        }

        nav a {
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            float: left;
            transition: background-color 0.3s;
        }

        nav a:hover {
            background-color: #714C8F;
        }

        h1 {
            color: #000;
            padding: 10px;
        }

        i{
            font-size: 72px;
        }

        .ri-checkbox-fill{
            font-size: 72px;
            color: #3C8D2F;
        }

    .errores {
    height: 100dvh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
   }


   .exito-container {
    height: 100dvh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
   }

    </style>
    <title></title>
</head>
<body>


<?php

//Propago la sesión
session_start();


$rol = isset($_SESSION['tipoUsuario']) ? $_SESSION['tipoUsuario'] : '';
$nombre = isset($_SESSION['nombreUsuario']) ? $_SESSION['nombreUsuario'] : 'invitado';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';


?>

<nav>
    
    <a href="jabonesscarlatti.php">Consultar Productos</a>
    

    <?php
    if ($rol === 'administrador') {
        
        echo '<a href="panel_administracion.php">Panel de Administración</a>';
        
    }

    if($rol === 'cliente') {
        echo '<a href="mostrarCesta.php">Carrito</a>';
    }

    // Verifica si hay un rol para mostrar el enlace correcto
    if (empty($rol)) {
        echo '<a href="login.php" style="float: right;">Iniciar Sesión</a>';
    } else {
        echo '<a href="logout.php" style="float: right;">Cerrar Sesión</a>';
    }
    ?>
    
</nav>
</body>
</html>