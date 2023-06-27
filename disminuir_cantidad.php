<?php
// Verificar si se ha enviado el formulario para disminuir la cantidad en el carrito
if (isset($_POST['disminuir_cantidad'])) {
    session_start();

    // Obtener el ID del producto desde el formulario
    $producto_id = $_POST['producto_id'];

    // Disminuir la cantidad del producto en el carrito
    if (isset($_SESSION['carrito'][$producto_id])) {
        // Si la cantidad es mayor a 1, disminuir en 1
        if ($_SESSION['carrito'][$producto_id] > 1) {
            $_SESSION['carrito'][$producto_id]--;
        } else {
            // Si la cantidad es igual a 1, eliminar el producto del carrito
            unset($_SESSION['carrito'][$producto_id]);
        }
    }

    echo "Cantidad disminuida del producto: " . $producto_id;
} else {
    // Si no se ha enviado el formulario, redirigir a la página del carrito
    header("Location: carrito.php");
    exit();
}
?>
