<!DOCTYPE html>
<html>
<head>
    <title>Iniciar sesión</title>
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
    <?php   
    // Verificar si el usuario ya ha iniciado sesión
    if (isset($_SESSION['user'])) {
        // Redirigir al usuario a la página tienda.php
        header("Location: tienda.php");
        exit;
    }

    // Verificar si se ha enviado el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtener los datos del formulario
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Conexión a la base de datos
        $conexion = mysqli_connect('localhost', 'root', '', 'tienda');

        // Verificar la conexión
        if (!$conexion) {
            die('Error al conectar a la base de datos: ' . mysqli_error($conexion));
        }

        // Consulta SQL para verificar el usuario y la contraseña
        $query = "SELECT * FROM usuarios WHERE email = '$username' AND password = '$password'";
        $result = mysqli_query($conexion, $query);

        // Verificar si se encontró un resultado
        if (mysqli_num_rows($result) === 1) {
            // Inicio de sesión exitoso
            // Obtener los datos del usuario
            $user = mysqli_fetch_assoc($result);
            $rol = $user['rol'];

            // Almacenar el nombre de usuario y el rol en la sesión
            $_SESSION['user'] = $username;
            $_SESSION['rol'] = $rol;

            // Cerrar la conexión a la base de datos
            mysqli_close($conexion);

            // Redirigir al usuario a la página tienda.php
            header("Location: tienda.php");
            exit;
        } else {
            echo "Usuario o contraseña incorrectos.";
        }

        // Cerrar la conexión a la base de datos
        mysqli_close($conexion);
    }
    ?>
    <h1>Iniciar sesión</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <label for="username">Correo electrónico:</label>
        <input type="email" name="username" id="username" required><br><br>

        <label for="password">Contraseña:</label>
        <input type="password" name="password" id="password" required><br><br>

        <input type="submit" value="Iniciar sesión">
    </form>
</div>
</body>
</html>
