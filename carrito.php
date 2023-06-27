<!DOCTYPE html>
<html>
<head>
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .productos {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .producto {
            width: 100%;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .producto img {
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
<nav class="barra-navegacion">
  <div class="navegacion-derecha">
    <?php
    session_start();
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
<h1>Carrito de Compras</h1>

<div class="productos">
    <?php
    // Establecer la conexión a la base de datos
    $conexion = mysqli_connect('localhost', 'root', '', 'tienda');
    if (!$conexion) {
        die('Error al conectar a la base de datos: ' . mysqli_error($conexion));
    }

    // Verificar si el carrito está vacío
    if (empty($_SESSION['carrito'])) {
        echo "<p>No hay productos en el carrito.</p>";
    } else {
        // Obtener los IDs de los productos del carrito
        $ids = array_keys($_SESSION['carrito']);
        $ids_string = implode(',', $ids);

        // Consulta para obtener los productos del carrito
        $consulta = "SELECT * FROM productos WHERE id IN ($ids_string)";
        $resultados = mysqli_query($conexion, $consulta);

        $totalCantidad = 0;
        $totalPrecio = 0;

        while ($row = mysqli_fetch_assoc($resultados)) {
            $productoId = $row['id'];
            $cantidad = $_SESSION['carrito'][$productoId];
            $subtotalPrecio = $row['precio'] * $cantidad;

            $totalCantidad += $cantidad;
            $totalPrecio += $subtotalPrecio;

            echo "<div class='producto'>";
            echo "<h3>" . $row['nombre'] . "</h3>";
            echo "<p><strong>Precio:</strong> " . $row['precio'] . "</p>";
            echo "<p><strong>Descripción:</strong> " . $row['descripcion'] . "</p>";
            echo "<p><strong>Cantidad:</strong> " . $cantidad . "</p>";
            echo "<p><strong>Subtotal:</strong> " . $subtotalPrecio . "</p>";
            echo "<button onclick='aumentarCantidad(" . $productoId . ")'>+</button>";
            echo "<button onclick='disminuirCantidad(" . $productoId . ")'>-</button>";
            echo "<button onclick='eliminarDelCarrito(" . $productoId . ")'>Eliminar</button>";
            echo "</div>";
        }

        echo "<p><strong>Precio Total:</strong> " . $totalPrecio . "</p>";
    }

    // Cerrar la conexión a la base de datos
    mysqli_close($conexion);
    ?>
</div>

<script>
    function eliminarDelCarrito(productoId) {
        // Envía una solicitud al servidor para eliminar el producto del carrito utilizando AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'eliminar_carrito.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Respuesta del servidor
                console.log(xhr.responseText);
                location.reload(); // Recargar la página después de eliminar el producto del carrito
            }
        };
        xhr.send('eliminar_carrito=true&producto_id=' + productoId);
    }

    function aumentarCantidad(productoId) {
        // Envía una solicitud al servidor para aumentar la cantidad del producto en el carrito utilizando AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'aumentar_cantidad.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Respuesta del servidor
                console.log(xhr.responseText);
                location.reload(); // Recargar la página después de aumentar la cantidad del producto en el carrito
            }
        };
        xhr.send('aumentar_cantidad=true&producto_id=' + productoId);
    }

    function disminuirCantidad(productoId) {
        // Envía una solicitud al servidor para disminuir la cantidad del producto en el carrito utilizando AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'disminuir_cantidad.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Respuesta del servidor
                console.log(xhr.responseText);
                location.reload(); // Recargar la página después de disminuir la cantidad del producto en el carrito
            }
        };
        xhr.send('disminuir_cantidad=true&producto_id=' + productoId);
    }
</script>
</body>
</html>
