# Jabones Scarlatti - Aplicación Web de Venta de Jabones Naturales

## Descripción

Jabones Scarlatti es una aplicación web desarrollada en PHP que permite la venta de jabones naturales elaborados en talleres prácticos de química de un instituto de secundaria. La aplicación es accesible desde la página de inicio `jabonescarlatti.php` y ofrece funcionalidades específicas para tres tipos de usuarios: Administradores, Clientes Registrados y Clientes No Registrados.

## Funcionalidades

## Administrador
- [x] Altas de productos
- [x] Bajas de productos
- [x] Control stock (si ya no hay más productos mostrar no stock y no bajar a -1)
- [x] Almacenar imágenes
- [x] Login
  - [x] Mail y contraseña

## Cliente
- [x] Comprar en tienda
- [x] Registro
  - [x] Mail y contraseña
- [x] Consulta datos (se debe paginar)

## Invitado
- [x] Consultar datos

## Base de Datos
- [x] Modificaciones
- [x] Se hace desde PHP

## Pendientes
- [x] Carrito de compra
- [x] Añadir productos por cantidad (2 artículos por cliente/mes)
- [x] Modificar cantidad de productos (2 unidades por compra)
- [x] Eliminar productos del carrito
- [x] Listar productos añadidos y fecha estimada de la entrega
- [x] Finalización/entrega del pedido
- [x] Mostrar importe
- [x] Mostrar artículos
- [x] Mostrar fecha estimada de entrega
- [x] Albarán PDF
- [x] Enviar albarán
- [x] Cliente
- [x] Departamento de química

## Aclaraciones
- [x] Itemcesta y cesta es lo mismo
- [x] Envío correo automático
- [x] Tabla administrador (correo y contraseña)
- [ ] Lista de deseos cuando ya no puede comprar más
- [ ] Guardar los productos que ya no se pueden comprar y que haya un registro
- [x] Tipo decimal (4,2)
- [x] Tipo date

## Pendientes no Asignados
- [x] En un fichero constante
- [x] Correo departamento de química
- [x] Número de días para siguiente pedido
- [x] Fecha de entrega 7 días

### Base de Datos

Tablas:
- CLIENTES
- PRODUCTOS
- CESTA
- ITEMCESTA
- PEDIDOS
- ITEMPEDIDO

Normalización hasta FNBC.

### Gestión del Carrito de Compra

- Añadir productos al carrito con cantidad (máximo 2 artículos por cliente al mes).
- Modificar cantidad de productos (máximo 2 unidades en total por compra).
- Eliminar productos del carrito.
- Listar productos añadidos con fecha estimada de entrega.

### Finalización y Entrega del Pedido

- Mostrar importe total del pedido.
- Mostrar artículos pedidos.
- Mostrar fecha de entrega estimada del pedido.
- Generar albarán en PDF.
- Enviar albarán/factura al cliente por email.
- Enviar albarán al departamento de química por email.

## Requisitos y Consideraciones

- No se incluye módulo de pago, ya que los pedidos se pagan en el momento de la recogida.
- La aplicación cumple con las normas de normalización de bases de datos hasta FNBC.
- Los usuarios no registrados pueden consultar información de productos pero no realizar compras.
- Se controla la cantidad de productos que un cliente puede comprar en un mes.
- El albarán se genera en formato PDF y se envía por email al cliente y al departamento de química.