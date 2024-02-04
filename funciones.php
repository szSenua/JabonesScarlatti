

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
        $mail->addAttachment($rutaPDFCompleta, 'albaran_pedido_' . $pedidoID . '.pdf');

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

function generarAlbaranPDF($pedidoID) {
    // Obtener la información del pedido y los elementos asociados
    $infoPedido = obtenerInfoPedido($pedidoID);

    // Configuración de FPDF
    class PDF extends FPDF {
        function Header() {
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(80);
            $this->Cell(30, 10, iconv('UTF-8', 'windows-1252','Albarán'), 1, 0, 'C');
            $this->Ln(20);
        }

        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, iconv('UTF-8', 'windows-1252','Página ') . $this->PageNo(), 0, 0, 'C');
        }
    }

// Crear instancia de PDF
$pdf = new PDF();
$pdf->AddPage();

// Agregar logo en el lado derecho
$logoPath = 'logo/logo.png';  // Reemplaza con la ruta real de tu logo
$logoWidth = 60;  // Ajusta el ancho del logo según tu diseño

// Calcular la posición X para el logo en el lado derecho
$logoX = $pdf->GetPageWidth() - $logoWidth - 10;  // 10 es el espacio desde el borde derecho

$pdf->Image($logoPath, $logoX, 10, $logoWidth);  // Ajusta las coordenadas y el tamaño según tu diseño


// Contenido del albarán
$pdf->SetFont('Arial', 'B', 12);
$pdf->MultiCell(0, 10, iconv('UTF-8', 'windows-1252', 'Número de Pedido: ' . $infoPedido[0]['pedidoID']), 0, 'L');
$pdf->MultiCell(0, 10, 'Fecha del Pedido: ' . $infoPedido[0]['fechaPedido'], 0, 'L');

// Mostrar información del cliente
$pdf->MultiCell(0, 10, 'Cliente: ' . $infoPedido[0]['nombreCliente'], 0, 'L');
$pdf->MultiCell(0, 10, iconv('UTF-8', 'windows-1252', 'Dirección: ' . $infoPedido[0]['direccionCliente']), 0, 'L');
$pdf->MultiCell(0, 10, iconv('UTF-8', 'windows-1252', 'Código Postal: ' . $infoPedido[0]['cpCliente']), 0, 'L');

$pdf->Ln(10);

$pdf->SetFont('Arial', 'B', 10);

// Encabezados de la tabla
$pdf->SetFont('Arial', 'B', 10);
$columnWidths = array(50, 20, 30, 30);
$columnHeaders = array('Producto', 'Cantidad', 'Precio Unitario', 'Precio Total');

// Calcular la posición X para centrar la tabla
$tableX = ($pdf->GetPageWidth() - array_sum($columnWidths)) / 2;

// Establecer la posición X para cada celda de encabezado
$currentX = $tableX;
foreach ($columnWidths as $width) {
    $pdf->SetX($currentX);
    $pdf->Cell($width, 10, current($columnHeaders), 1, 0, 'C');
    $currentX += $width; // Incrementar la posición X para la próxima celda
    next($columnHeaders);
}
$pdf->Ln();

$pdf->SetFont('Arial', '', 10);

$totalCompra = 0;  // Inicializar el total de la compra

foreach ($infoPedido as $producto) {
    // Contenido de la tabla
    $currentX = $tableX; // Restablecer la posición X para cada línea
    $pdf->SetX($currentX);
    $pdf->Cell($columnWidths[0], 10, iconv('UTF-8', 'windows-1252', $producto['nombreProducto']), 1, 0);
    $currentX += $columnWidths[0]; // Incrementar la posición X para la próxima celda
    $pdf->SetX($currentX);
    $pdf->Cell($columnWidths[1], 10, $producto['cantidad'], 1, 0, 'C');
    $currentX += $columnWidths[1]; // Incrementar la posición X para la próxima celda
    $pdf->SetX($currentX);
    $pdf->Cell($columnWidths[2], 10, $producto['precioProducto'] . iconv('UTF-8', 'windows-1252',' €'), 1, 0, 'C');
    $currentX += $columnWidths[2]; // Incrementar la posición X para la próxima celda
    $pdf->SetX($currentX);
    $pdf->Cell($columnWidths[3], 10, $producto['precioTotal'] . iconv('UTF-8', 'windows-1252',' €'), 1, 1, 'C');

    $totalCompra += $producto['precioTotal'];  // Sumar al total de la compra
}

// Fila con colspan para el precio total
$pdf->SetFont('Arial', 'B', 10);
$currentX = $tableX; // Restablecer la posición X para la fila de total
$pdf->SetX($currentX);
$pdf->Cell(array_sum($columnWidths) - $columnWidths[3], 10, 'Total', 1, 0, 'C');  // Resta el ancho de la última columna
$currentX += (array_sum($columnWidths) - $columnWidths[3]); // Incrementar la posición X para la próxima celda
$pdf->SetX($currentX);
$pdf->Cell($columnWidths[3], 10, iconv('UTF-8', 'windows-1252', $totalCompra . ' €'), 1, 1, 'C');



    // Guardar o mostrar el PDF 
    $pdfPath = 'albaran_pedido_' . $pedidoID . '.pdf';
    $pdf->Output('F', $pdfPath); // Guardar el PDF en el servidor
    // $pdf->Output(); // Mostrar el PDF en el navegador

    $asunto = "Pedido " . $pedidoID;
    $destinatarios = array();

    //============Crear cuenta en axigen======================
    $destinatarios [] = "depquimica@domenico.es";

    //===============Descomentar y probar en clase========================
    //function enviarEmail($destinatarios, $asunto, $mensaje, $pdfPath, $usuario, $pass);
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

