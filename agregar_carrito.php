<?php
// Verificar si se ha enviado el formulario para agregar al carrito
if (isset($_POST['agregar_carrito'])) {
    session_start();

    // Obtener el ID del producto y la cantidad desde el formulario
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];

    // Crear o actualizar el carrito en la sesión
    if (isset($_SESSION['carrito'])) {
        // Si el carrito ya existe, agregar el producto y su cantidad
        $_SESSION['carrito'][$producto_id] += $cantidad;
    } else {
        // Si el carrito no existe, crearlo y agregar el producto y su cantidad
        $_SESSION['carrito'] = array($producto_id => $cantidad);
    }

    // Redirigir de vuelta a la página de la tienda
    header("Location: tienda.php");
    exit();
} else {
    // Si no se ha enviado el formulario, redirigir a la página de la tienda
    header("Location: tienda.php");
    exit();
}
?>
