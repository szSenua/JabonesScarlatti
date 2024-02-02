# Jabones Scarlatti - Aplicación Web de Venta de Jabones Naturales

## Descripción

Jabones Scarlatti es una aplicación web desarrollada en PHP que permite la venta de jabones naturales elaborados en talleres prácticos de química de un instituto de secundaria. La aplicación es accesible desde la página de inicio `jabonescarlatti.php` y ofrece funcionalidades específicas para tres tipos de usuarios: Administradores, Clientes Registrados y Clientes No Registrados.

## Funcionalidades

## Administrador
- [ ] Altas de productos
- [ ] Bajas de productos
- [ ] Control stock (si ya no hay más productos mostrar no stock y no bajar a -1)
- [ ] Almacenar imágenes
- [ ] Login
  - [ ] Mail y contraseña

## Cliente
- [ ] Comprar en tienda
- [ ] Registro
  - [ ] Mail y contraseña
- [ ] Consulta datos (se debe paginar)

## Invitado
- [ ] Consultar datos

## Base de Datos
- [ ] Modificaciones
- [ ] Se hace desde PHP

## Pendientes
- [ ] Carrito de compra
- [ ] Añadir productos por cantidad (2 artículos por cliente/mes)
- [ ] Modificar cantidad de productos (2 unidades por compra)
- [ ] Eliminar productos del carrito
- [ ] Listar productos añadidos y fecha estimada de la entrega
- [ ] Finalización/entrega del pedido
- [ ] Mostrar importe
- [ ] Mostrar artículos
- [ ] Mostrar fecha estimada de entrega
- [ ] Albarán PDF
- [ ] Enviar albarán
- [ ] Cliente
- [ ] Departamento de química

## Aclaraciones
- [ ] Itemcesta y cesta es lo mismo
- [ ] Envío correo automático
- [ ] Tabla administrador (correo y contraseña)
- [ ] Lista de deseos cuando ya no puede comprar más
- [ ] Guardar los productos que ya no se pueden comprar y que haya un registro
- [ ] Tipo decimal (4,2)
- [ ] Tipo date

## Pendientes no Asignados
- [ ] En un fichero constante
- [ ] Correo departamento de química
- [ ] Número de días para siguiente pedido
- [ ] Fecha de entrega 7 días

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