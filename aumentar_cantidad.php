<?php
// Verificar si se ha enviado el formulario para aumentar la cantidad en el carrito
if (isset($_POST['aumentar_cantidad'])) {
    session_start();

    // Obtener el ID del producto desde el formulario
    $producto_id = $_POST['producto_id'];

    // Aumentar la cantidad del producto en el carrito
    if (isset($_SESSION['carrito'][$producto_id])) {
        $_SESSION['carrito'][$producto_id]++;
    }

    echo "Cantidad aumentada del producto: " . $producto_id;
} else {
    // Si no se ha enviado el formulario, redirigir a la página del carrito
    header("Location: carrito.php");
    exit();
}
?>
