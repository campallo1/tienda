<?php
// Verificar si el usuario ha iniciado sesión y tiene el rol de "trabajador"
session_start();
if (!isset($_SESSION['user']) || $_SESSION['rol'] !== 'trabajador') {
    // El usuario no ha iniciado sesión o no tiene el rol de "trabajador"
    // Redirigir a la página de inicio o mostrar un mensaje de acceso denegado
    header("Location: tienda.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Eliminar Producto</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function eliminarProducto(id) {
            if (confirm("¿Estás seguro de que quieres eliminar este producto?")) {
                window.location.href = "eliminar_producto.php?id=" + id;
            }
        }
    </script>
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
    <h1>Eliminar Producto</h1>
    <div class="productos">
        <?php
        if (isset($_SESSION['user'])) {
            $conexion = mysqli_connect('localhost', 'root', '', 'tienda');
            if (!$conexion) {
                die('Error al conectar a la base de datos: ' . mysqli_error($conexion));
            }

            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $consulta = "DELETE FROM productos WHERE id = $id";
                if (mysqli_query($conexion, $consulta)) {
                    echo "Producto eliminado correctamente.";
                } else {
                    echo "Error al eliminar el producto: " . mysqli_error($conexion);
                }
            }

            $consulta = "SELECT * FROM productos";
            $resultados = mysqli_query($conexion, $consulta);

            if (mysqli_num_rows($resultados) > 0) {
                while ($row = mysqli_fetch_assoc($resultados)) {
                    echo "<div class='producto'>";
                    echo "<h3>" . $row['nombre'] . "</h3>";
                    echo "<p><strong>Precio:</strong> " . $row['precio'] . "</p>";
                    echo "<p><strong>Descripción:</strong> " . $row['descripcion'] . "</p>";
                    echo "<button onclick=\"eliminarProducto(" . $row['id'] . ")\">Eliminar</button>";
                    echo "</div>";
                }
            } else {
                echo "No hay productos en la base de datos.";
            }

            mysqli_close($conexion);
        } else {
            echo "Debes iniciar sesión para acceder a esta página.";
        }
        ?>
    </div>
</div>
</body>
</html>
