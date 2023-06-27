<!DOCTYPE html>
<html>
<head>
    <title>Registrarse</title>
    <link rel="stylesheet" href="style.css">
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
<div class="container">
    <h1>Registrarse</h1>
    <?php
    // Verificar si se ha enviado el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtener los datos del formulario
        $email = $_POST['email'];
        $password = $_POST['password'];
        $username = $_POST['username'];

        // Conexión a la base de datos
        $conexion = mysqli_connect('localhost', 'root', '', 'tienda');

        // Verificar la conexión
        if (!$conexion) {
            die('Error al conectar a la base de datos: ' . mysqli_error($conexion));
        }

        // Preparar los datos para la consulta
        $email = mysqli_real_escape_string($conexion, $email);
        $password = mysqli_real_escape_string($conexion, $password);
        $username = mysqli_real_escape_string($conexion, $username);

        // Verificar si el correo electrónico termina en "@tienda.com"
        if (substr($email, -11) === "@tienda.com") {
            // Si el correo electrónico termina en "@tienda.com",
            // establecer el rol como "trabajador"
            $rol = "trabajador";
        } else {
            // De lo contrario, establecer el rol como "cliente"
            $rol = "cliente";
        }

        // Consulta SQL para insertar el usuario en la base de datos
        $query = "INSERT INTO usuarios (username, email, password, rol) VALUES ('$username', '$email', '$password', '$rol')";

        // Ejecutar la consulta
        if (mysqli_query($conexion, $query)) {
            echo "Registro exitoso. Ahora puedes <a href='login.php'>iniciar sesión</a>.";
        } else {
            echo "Error al registrar el usuario: " . mysqli_error($conexion);
        }

        // Cerrar la conexión a la base de datos
        mysqli_close($conexion);
    }
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br><br>

        <label for="email">Correo electrónico:</label>
        <input type="email" name="email" id="email" required><br><br>

        <label for="password">Contraseña:</label>
        <input type="password" name="password" id="password" required><br><br>

        <input type="submit" value="Registrarse">
    </form>
</div>
</body>
</html>
