<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Establecer conexión a la base de datos
$server = "localhost";
$username_db = "root";
$password_db = "";
$db = "agencia_viajes";

$conexion = new mysqli($server, $username_db, $password_db, $db);

// Verificar la conexión
if ($conexion->connect_errno) {
    die("Conexión fallida: " . $conexion->connect_errno);
}

// Obtener el usuario y la contraseña del formulario
$usuario = $_POST['usuario'];
$password = $_POST['password'];

// Preparar la consulta para buscar el usuario en la base de datos
$stmt = $conexion->prepare("SELECT contraseña FROM usuarios WHERE nombre = ?");
$stmt->bind_param("s", $usuario);
$stmt->execute();
$stmt->store_result();

// Verificar si el usuario existe
if ($stmt->num_rows > 0) {
    // Obtener la contraseña de la base de datos
    $stmt->bind_result($contraseña_bdd);
    $stmt->fetch();

    // Verificar si la contraseña ingresada es correcta
    if ($contraseña_bdd === $password) {
        // Redirigir al usuario a la página correspondiente
        header("Location: pagina_usuario.php");
        exit();
    } else {
        echo "Contraseña incorrecta.";
    }
} else {
    echo "El nombre de usuario no existe.";
}

$stmt->close();
$conexion->close();
?>
