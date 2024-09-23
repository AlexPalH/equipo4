<?php
// Conexión a la base de datos
$server = "localhost";
$username = "root";
$password = "";
$db = "agencia_viajes";
$conexion = new mysqli($server, $username, $password, $db);

if ($conexion->connect_errno) {
    die("Conexión fallida: " . $conexion->connect_errno);
}

// Nombre de usuario fijo
$nombre_usuario = "usuarioA"; // Cambiado a usuarioA

// Consultar el perfil del usuario y sus permisos
$query = "SELECT id, nombre, tipo_rol AS rol, permiso_insertar, permiso_borrar, permiso_modificar, permiso_consultar FROM usuarios WHERE nombre = '$nombre_usuario'";
$resultado = $conexion->query($query);

if ($resultado && $resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    $id_usuario = $fila['id'];
    $nombre_usuario = $fila['nombre'];
    $rol = $fila['rol']; // Ahora 'rol' hace referencia a 'tipo_rol'
    $permiso_insertar = $fila['permiso_insertar'];
    $permiso_borrar = $fila['permiso_borrar'];
    $permiso_modificar = $fila['permiso_modificar'];
    $permiso_consultar = $fila['permiso_consultar'];

    // Ahora mostramos la interfaz según los permisos obtenidos
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página de Usuario A</title>
    <link rel="stylesheet" href="estiloA.css">
</head>
<body>
    <h1>Bienvenido a Aerolineas el JIMMY</h1>
  
    <ul>
        <?php if ($permiso_insertar) { ?>
            <li><a href="insertar_registro.php">Reservar viaje</a></li>
        <?php } else { ?>
            <li><a href="#" onclick="alert('No tienes acceso a esto ya que han cambiado tu rol a <?php echo htmlspecialchars($rol); ?>');">Insertar Registro</a></li>
        <?php } ?>
        
        <?php if ($permiso_borrar) { ?>
            <li><a href="borrar_registro.php">Borrar viajes</a></li>
        <?php } else { ?>
            <li><a href="#" onclick="alert('No tienes acceso a esto ya que han cambiado tu rol a <?php echo htmlspecialchars($rol); ?>');">Borrar Registro</a></li>
        <?php } ?>

        <?php if ($permiso_modificar) { ?>
            <li><a href="modificar_registro.php">Gestionar viajes</a></li>
        <?php } else { ?>
            <li><a href="#" onclick="alert('No tienes acceso a esto ya que han cambiado tu rol a <?php echo htmlspecialchars($rol); ?>');">Modificar Registro</a></li>
        <?php } ?>

        <?php if ($permiso_consultar) { ?>
            <li><a href="consultar_registros.php">Consultar viajes</a></li>
        <?php } else { ?>
            <li><a href="#" onclick="alert('No tienes acceso a esto ya que han cambiado tu rol a <?php echo htmlspecialchars($rol); ?>');">Consultar Registros</a></li>
        <?php } ?>
    </ul>

    <!-- Botón para regresar a la página anterior -->
    <button onclick="goBack()">Regresar</button>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>
<?php
} else {
    echo "Usuario no encontrado en la base de datos.";
}

$conexion->close();
?>
