<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Clientes y Reservas</title>
    <link rel="stylesheet" href="estiloconsultar.css">
</head>
<body>
    <?php
    // Conexión a la base de datos
    $server = "localhost";
    $username = "root";
    $password = "";
    $db = "agencia_viajes";
    $conexion = new mysqli($server, $username, $password, $db);

    if($conexion->connect_errno) {
        die("Conexión fallida: " . $conexion->connect_errno);
    }

    // Consulta para obtener clientes y sus reservas
    $query = "
        SELECT 
            c.nombre, 
            c.direccion, 
            c.numero_de_telefono, 
            r.viaje, 
            r.precio, 
            r.fecha 
        FROM 
            clientes c 
        JOIN 
            reservas r ON c.id = r.cliente
    ";

    $resultado = $conexion->query($query);

    if ($resultado->num_rows > 0) {
        // Mostrar los resultados en una tabla HTML
        echo "<h2>Lista de Clientes y Reservas</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Nombre</th><th>Dirección</th><th>Número de Teléfono</th><th>Viaje</th><th>Precio</th><th>Fecha</th></tr>";

        while ($fila = $resultado->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $fila['nombre'] . "</td>";
            echo "<td>" . $fila['direccion'] . "</td>";
            echo "<td>" . $fila['numero_de_telefono'] . "</td>";
            echo "<td>" . $fila['viaje'] . "</td>";
            echo "<td>" . $fila['precio'] . "</td>";
            echo "<td>" . $fila['fecha'] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No se encontraron clientes o reservas.";
    }

    $conexion->close();
    ?>

    <br>
    <button onclick="window.history.back()">Regresar a la página anterior</button>
</body>
</html>
