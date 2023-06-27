<!DOCTYPE html>
<html>
<head>
    <title>Tienda de Productos</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .productos {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .producto {
            width: 23%;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .producto img {
            width: 100%;
            height: auto;
        }

        .activo {
            font-weight: bold;
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


<h1>Tienda de Productos</h1>

<div class="productos">
    <?php
    // Conexión a la base de datos
    $conexion = mysqli_connect('localhost', 'root', '', 'tienda');

    // Verificar la conexión
    if (!$conexion) {
        die('Error al conectar a la base de datos: ' . mysqli_error($conexion));
    }

    // Obtener todos los productos de la base de datos
    $consulta = "SELECT * FROM productos";
    $resultados = mysqli_query($conexion, $consulta);

    // Verificar si hay productos en la base de datos
    if (mysqli_num_rows($resultados) > 0) {
        // Mostrar los productos
        while ($row = mysqli_fetch_assoc($resultados)) {
            echo "<div class='producto'>";
            echo "<h3>" . $row['nombre'] . "</h3>";
            echo "<p><strong>Precio:</strong> " . $row['precio'] . "</p>";
            echo "<p><strong>Descripción:</strong> " . $row['descripcion'] . "</p>";
            echo "<img src=\"" . $row['imagen'] . "\">";
            echo "<button onclick='agregarAlCarrito(" . $row['id'] . ")'>Agregar al carrito</button>";
            echo "</div>";
        }
    } else {
        echo "No hay productos en la base de datos.";
    }

    // Cerrar la conexión a la base de datos
    mysqli_close($conexion);
    ?>
</div>

<script>
    function agregarAlCarrito(productoId) {
        // Envía una solicitud al servidor para agregar el producto al carrito utilizando AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'agregar_carrito.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Respuesta del servidor
                console.log(xhr.responseText);
            }
        };
        xhr.send('agregar_carrito=true&producto_id=' + productoId + '&cantidad=1');
    }
</script>
</body>
</html>
