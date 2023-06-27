<?php

// Verificar si el usuario ha iniciado sesión y tiene el rol de "trabajador"
session_start();
if (!isset($_SESSION['user']) || $_SESSION['rol'] !== 'trabajador') {
    // El usuario no ha iniciado sesión o no tiene el rol de "trabajador"
    // Redirigir a la página de inicio o mostrar un mensaje de acceso denegado
    header("Location: tienda.php");
    exit;
}

// Función para obtener el último ID de producto en la base de datos
function obtenerUltimoID() {
    // Configurar la conexión a la base de datos (modifica los valores según tu configuración)
    $host = "localhost";
    $usuario = "root";
    $contrasena = "";
    $base_datos = "tienda";

    // Crear una instancia de PDO
    try {
        $conexion = new PDO("mysql:host=$host;dbname=$base_datos", $usuario, $contrasena);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Error de conexión a la base de datos: " . $e->getMessage();
        exit;
    }

    // Obtener el último ID de producto
    $sentencia = $conexion->prepare("SELECT MAX(id) AS ultimo_id FROM productos");
    $sentencia->execute();
    $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);

    return $resultado['ultimo_id'];
}

// Función para guardar los datos del producto en la base de datos
function guardarProducto($id, $nombre, $precio, $descripcion, $imagen) {
    // Configurar la conexión a la base de datos (modifica los valores según tu configuración)
    $host = "localhost";
    $usuario = "root";
    $contrasena = "";
    $base_datos = "tienda";

    // Crear una instancia de PDO
    try {
        $conexion = new PDO("mysql:host=$host;dbname=$base_datos", $usuario, $contrasena);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Error de conexión a la base de datos: " . $e->getMessage();
        exit;
    }

    // Generar la URL de la imagen
    $urlImagen = "imagenes/" . $id . ".jpg";

    // Mover la imagen al directorio "imagenes" con el nombre del ID del producto
    if (move_uploaded_file($imagen, $urlImagen)) {
        // Preparar la sentencia SQL
        $sentencia = $conexion->prepare("INSERT INTO productos (id, nombre, precio, descripcion, imagen) VALUES (?, ?, ?, ?, ?)");

        // Vincular los parámetros
        $sentencia->bindParam(1, $id);
        $sentencia->bindParam(2, $nombre);
        $sentencia->bindParam(3, $precio);
        $sentencia->bindParam(4, $descripcion);
        $sentencia->bindParam(5, $urlImagen);

        // Ejecutar la sentencia SQL
        try {
            $sentencia->execute();
            echo "Producto guardado en la base de datos.";
        } catch (PDOException $e) {
            echo "Error al guardar el producto en la base de datos: " . $e->getMessage();
        }
    } else {
        echo "Error al mover la imagen al directorio 'imagenes'.";
    }
}

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = $_POST["nombre"];
    $precio = $_POST["precio"];
    $descripcion = $_POST["descripcion"];

    // Generar un ID único para el producto (puedes adaptar esta lógica según tus necesidades)
    $id = obtenerUltimoID() + 1;

    // Verificar si se ha subido una imagen
    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == UPLOAD_ERR_OK) {
        $archivoImagen = $_FILES["imagen"]["tmp_name"];
        guardarProducto($id, $nombre, $precio, $descripcion, $archivoImagen);
    } else {
        echo "Error al subir la imagen.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="barra-navegacion">
  <div class="navegacion-derecha">
    <?php
    if (isset($_SESSION['user'])) {
      if ($_SESSION['rol'] === 'cliente') {
        echo '<div class="enlaces-principales">';
        echo '<a class="enlace-navegacion" href="tienda.php">Tienda</a>';
        echo '<a class="enlace-navegacion carrito" href="carrito.php">Carrito</a>';
        echo '</div>'; // Cierra el div de los enlaces principales

        echo '<div class="email-extension">';
        echo '<p>' . $_SESSION['user'] . '</p>'; // Mostrar el correo electrónico
        echo '<div class="dropdown-content">';
        echo '<a href="logout.php">Cerrar sesión</a>';
        echo '</div>'; // Cierra el div del menú desplegable
        echo '</div>'; // Cierra el div de la extensión del correo electrónico
      } elseif ($_SESSION['rol'] === 'trabajador') {
        echo '<div class="enlaces-principales">';
        echo '<a class="enlace-navegacion" href="tienda.php">Tienda</a>';
        echo '<a class="enlace-navegacion carrito" href="carrito.php">Carrito</a>';
        echo '<a class="enlace-navegacion" href="guardar_producto.php">Agregar</a>';
        echo '<a class="enlace-navegacion" href="eliminar_producto.php">Eliminar</a>';
        echo '</div>'; // Cierra el div de los enlaces principales

        echo '<div class="email-extension">';
        echo '<p>' . $_SESSION['user'] . '</p>'; // Mostrar el correo electrónico
        echo '<div class="dropdown-content">';
        echo '<a href="logout.php">Cerrar sesión</a>';
        echo '</div>'; // Cierra el div del menú desplegable
        echo '</div>'; // Cierra el div de la extensión del correo electrónico
      }
    } else {
      echo '<div class="enlaces-principales">';
      echo '<a class="enlace-navegacion" href="tienda.php">Tienda</a>';
      echo '<a class="enlace-navegacion" href="login.php">Iniciar sesión</a>';
      echo '<a class="enlace-navegacion" href="signup.php">Registrarse</a>';
      echo '</div>'; // Cierra el div de los enlaces principales
    }
    ?>
  </div>
</nav>
<div class="container">
    <h1>Agregar Producto</h1>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required><br><br>

        <label for="precio">Precio:</label>
        <input type="text" name="precio" id="precio" required><br><br>

        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" id="descripcion" required></textarea><br><br>

        <label for="imagen">Imagen:</label>
        <input type="file" name="imagen" id="imagen" required><br><br>

        <input type="submit" value="Guardar Producto">
    </form>
</div>
</body>
</html>
