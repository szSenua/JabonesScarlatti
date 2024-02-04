

<?php

require_once 'conecta.php';

//Función para obtener el tipo de usuario


function obtenerInfoUsuario($email, $contrasena) {
    global $con;

    // Consultar el tipo de usuario en la tabla administradores
    $queryAdmin = "SELECT email, nombre, contrasena FROM administradores WHERE email=:email AND contrasena=:contrasena";
    $stmtAdmin = $con->prepare($queryAdmin);
    $stmtAdmin->bindParam(':email', $email);
    $stmtAdmin->bindParam(':contrasena', $contrasena);
    $stmtAdmin->execute();

    // Si es un administrador, devolver información del administrador
    if ($stmtAdmin->rowCount() > 0) {
        $adminData = $stmtAdmin->fetch(PDO::FETCH_ASSOC);
        // Cerrar conexión
        require_once 'desconecta.php';
        return array(
            'tipo' => 'administrador',
            'email' => $adminData['email'],
            'nombre' => $adminData['nombre']
        );
    }

    // Consultar el tipo de usuario en la tabla clientes
    $queryCliente = "SELECT email, nombre, contrasena FROM clientes WHERE email=:email AND contrasena=:contrasena";
    $stmtCliente = $con->prepare($queryCliente);
    $stmtCliente->bindParam(':email', $email);
    $stmtCliente->bindParam(':contrasena', $contrasena);
    $stmtCliente->execute();

    // Si es un cliente, devolver información del cliente
    if ($stmtCliente->rowCount() > 0) {
        $clienteData = $stmtCliente->fetch(PDO::FETCH_ASSOC);
        // Cerrar conexión
        require_once 'desconecta.php';
        return array(
            'tipo' => 'cliente',
            'email' => $clienteData['email'],
            'nombre' => $clienteData['nombre']
        );
    }

    // Si no se encuentra en ninguna tabla, devolver null
    require_once 'desconecta.php';
    return null;
}




function pintaLoginconParam($email, $contrasena, $errores) {
    echo '
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title></title>
</head>
<body>
    ';


    echo '<div class="login"><form action="login.php" method="post" class="form">';
    
    // Mostrar errores solo si la variable $errores no está vacía
    if (!empty($errores)) {
        echo '<div class="alert alert-danger" role="alert">
            <ul>';
        foreach ($errores as $error) {
            echo '<li>' . $error . '</li>';
        }
        echo '</ul></div>';
    }

    echo '
        <h2>Bienvenid@ al login</h2>
        <input type="text" name="email" value="' . htmlspecialchars($email) . '" placeholder="email">
        <input type="password" name="contrasena" value="' . htmlspecialchars($contrasena) . '" placeholder="Contraseña">
        <input type="submit" value="Enviar" class="submit">
        <a href="registro.php">Registrarse</a>
      </form>
    </div>
    </body>
    </html>';
}


//función para mandar el email

function enviarEmail($destinatarios, $asunto, $mensaje, $rutaPDFCompleta, $usuario, $pass) {
    include_once('PHPMailer-master/src/PHPMailer.php');
    include_once('PHPMailer-master/src/SMTP.php');

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Mailer = "SMTP";
    $mail->SMTPAuth = true;
    $mail->isHtml(true);
    $mail->SMTPAutoTLS = false;
    $mail->Port = 25;
    $mail->CharSet = 'UTF-8';
    $mail->Host = "localhost";
    $mail->Username = $usuario;
    $mail->Password = $pass;
    $mail->setFrom("christina@domenico.es");
    //$mail->SMTPDebug = 2;  // Enable verbose debug output

    if (is_array($destinatarios)) {
        foreach ($destinatarios as $destinatario) {
            $mail->addAddress($destinatario);
        }

        $mail->Subject = $asunto;
        $mail->Body = $mensaje;

        // Adjuntar el archivo PDF
        $mail->addAttachment($rutaPDFCompleta, 'document.pdf');

        if (!$mail->send()) {
            echo $mail->ErrorInfo;
        } else {
            // El email ha sido enviado.
            echo '<h2>Correo enviado con éxito</h2>';

            //Arreglar el botón
            echo '<br><a href="index.php"><button name="volver">Volver</button></a>';
        }
    }
}

function generarNombrePDF($email, $fechaPedido) {
    // Limpiar el email para evitar problemas con caracteres no permitidos en nombres de archivos
    $emailLimpio = preg_replace('/[^a-zA-Z0-9]+/', '', $email);
    
    // Formatear la fecha del pedido para agregarla al nombre del archivo
    $fechaPedidoFormateada = (new DateTime($fechaPedido))->format('Ymd_His');

    // Concatenar el email y la fecha formateada para obtener el nombre del PDF
    $pdfNombre = 'albaran_' . $emailLimpio . '_' . $fechaPedidoFormateada . '.pdf';

    return $pdfNombre;
}

function generarAlbaranPDF($pedidoID) {
    // Obtener la información del pedido y los elementos asociados
    $infoPedido = obtenerInfoPedido($pedidoID);

    // Configuración de FPDF
    class PDF extends FPDF {
        function Header() {
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(80);
            $this->Cell(30, 10, 'Albaran', 1, 0, 'C');
            $this->Ln(20);
        }

        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
        }
    }

    // Crear instancia de PDF
    $pdf = new PDF();
    $pdf->AddPage();

  // Contenido del albarán
  $pdf->SetFont('Arial', 'B', 12);
  $pdf->Cell(0, 10, 'Numero de Pedido: ' . $infoPedido[0]['pedidoID'], 0, 1);
  $pdf->Cell(0, 10, 'Fecha del Pedido: ' . $infoPedido[0]['fechaPedido'], 0, 1);

  // Mostrar información del cliente
  $pdf->Cell(0, 10, 'Cliente: ' . $infoPedido[0]['nombreCliente'], 0, 1);
  $pdf->Cell(0, 10, 'Dirección: ' . $infoPedido[0]['direccionCliente'], 0, 1);
  $pdf->Cell(0, 10, 'Código Postal: ' . $infoPedido[0]['cpCliente'], 0, 1);
    

    $pdf->Ln(10);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(40, 10, 'Producto', 1, 0);
    $pdf->Cell(30, 10, 'Cantidad', 1, 0);
    $pdf->Cell(30, 10, 'Precio Unitario', 1, 0);
    $pdf->Cell(30, 10, 'Precio Total', 1, 1);

    $pdf->SetFont('Arial', '', 10);

    $totalCompra = 0;  // Inicializar el total de la compra

    foreach ($infoPedido as $producto) {
        $pdf->Cell(40, 10, iconv('UTF-8', 'ISO-8859-1', $producto['nombreProducto']), 1, 0);
        $pdf->Cell(30, 10, $producto['cantidad'], 1, 0);
        $pdf->Cell(30, 10, $producto['precioProducto'], 1, 0);
        $pdf->Cell(30, 10, $producto['precioTotal'], 1, 1);

        $totalCompra += $producto['precioTotal'];  // Sumar al total de la compra
    }

    // Agregar fila nueva con colspan para el precio total
    $pdf->Cell(100, 10, '', 0, 0);  // Celda vacía
    $pdf->Cell(30, 10, 'Total', 1, 0);
    $pdf->Cell(0, 10, $totalCompra . ' €', 1, 1);

    // Guardar o mostrar el PDF 
    $pdfPath = 'albaran_pedido_' . $pedidoID . '.pdf';
    $pdf->Output('F', $pdfPath); // Guardar el PDF en el servidor
    // $pdf->Output(); // Mostrar el PDF en el navegador

    $asunto = "Pedido " . $pedidoID;
    $destinatarios = array();

    //============Crear cuenta en axigen======================
    $destinatarios [] = "depquimica@domenico.es";

    //===============Descomentar y probar en clase========================
    //function enviarEmail($destinatarios, $asunto, $mensaje, $pdf, $usuario, $pass);
}




function obtenerInfoPedido($pedidoID){
    global $con;

    $query = "SELECT p.*, ip.unidades AS cantidad, pr.nombre AS nombreProducto, pr.precio AS precioProducto,
              (ip.unidades * pr.precio) AS precioTotal,
              c.nombre AS nombreCliente, c.direccion AS direccionCliente, c.cp AS cpCliente
              FROM pedidos p
              JOIN itempedido ip ON p.pedidoID = ip.pedidoID
              JOIN productos pr ON ip.productoID = pr.productoID
              JOIN clientes c ON p.email = c.email
              WHERE p.pedidoID = ?";
    
    $stmt = $con->prepare($query);
    $stmt->bindParam(1, $pedidoID, PDO::PARAM_INT);
    $stmt->execute();

    // Obtener el resultado como un array asociativo
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function pintaRegistroSolicitanteConParam($nombre, $contrasena, $direccion, $email, $cp, $telefono, $errores) {
    echo '<div class="registro"><form action="registro.php" method="post" class="form">';
    
    // Mostrar errores solo si la variable $errores no está vacía
    if (!empty($errores)) {
        echo '<div class="alert alert-danger" role="alert">
            <ul>';
        foreach ($errores as $error) {
            echo '<li>' . $error . '</li>';
        }
        echo '</ul></div>';
    }

    echo '
        <h2>Registro</h2>
        
        
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" value="' . htmlspecialchars($nombre) . '" >

            <label for="contrasena">Contraseña:</label>
            <input type="password" name="contrasena" value="' . htmlspecialchars($contrasena) . '" >

            <label for="direccion">Direccion:</label>
            <input type="text" name="direccion" value="' . htmlspecialchars($direccion) . '" >

            <label for="email">Email:</label>
            <input type="email" name="email" value="' . htmlspecialchars($email) . '" >

            <label for="cp">Código Postal:</label>
            <input type="text" name="cp" value="' . htmlspecialchars($cp) . '" >

            <label for="telefono">Telefono:</label>
            <input type="text" name="telefono" value="' . htmlspecialchars($telefono) . '" >
        

        
        <input type="submit" value="Registrar" class="submit">
   
    
      </form>
    </div>';
}

//función para validar el correo

function validarCorreo($correo) {
    return filter_var($correo, FILTER_VALIDATE_EMAIL) !== false;
}

//Función para validar un teléfono fijo o móvil
function validarTelefono($telefono) {
    // Eliminar cualquier caracter que no sea dígito
    $numero = preg_replace("/[^0-9]/", "", $telefono);

    // Comprobar si el número tiene un formato válido para teléfonos españoles
    if (preg_match("/^(34)?[6789]\d{8}$/", $numero)) {
        return true;
    }

    return false;
}



?>
</body>
</html>

