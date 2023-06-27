<?php
// Verificar si se ha enviado el formulario para eliminar del carrito
if (isset($_POST['eliminar_carrito'])) {
    session_start();

    // Obtener el ID del producto desde el formulario
    $producto_id = $_POST['producto_id'];

    // Eliminar el producto del carrito
    if (isset($_SESSION['carrito'][$producto_id])) {
        unset($_SESSION['carrito'][$producto_id]);
    }

    echo "Producto eliminado del carrito: " . $producto_id;
} else {
    // Si no se ha enviado el formulario, redirigir a la página del carrito
    header("Location: carrito.php");
    exit();
}
?>
