<!DOCTYPE html> 
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario para Borrar Cliente y Reserva</title>
    <link rel="stylesheet" href="estiloborrar.css">
</head>
<body>
    <h2>Formulario para Borrar Cliente y Reserva</h2>
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

    // Procesamiento del formulario cuando se envía
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id_cliente = isset($_POST['id_cliente']) ? mysqli_real_escape_string($conexion, $_POST['id_cliente']) : '';
        $id_reservas = isset($_POST['id_reservas']) ? mysqli_real_escape_string($conexion, $_POST['id_reservas']) : '';

        // Si se ingresó el ID del cliente
        if (!empty($id_cliente)) {
            // Eliminar reservas relacionadas con el cliente
            $query_reservas = "DELETE FROM reservas WHERE cliente = '$id_cliente'";
            if (!$conexion->query($query_reservas)) {
                echo "Error al borrar reservas del cliente: " . $conexion->error;
            }

            // Preparar la consulta SQL para borrar el cliente
            $query_cliente = "DELETE FROM clientes WHERE id = '$id_cliente'";
            if ($conexion->query($query_cliente)) {
                if ($conexion->affected_rows > 0) {
                    echo "Cliente borrado correctamente.";
                } else {
                    echo "No se encontró ningún cliente con ese ID.";
                }
            } else {
                echo "Error al borrar el cliente: " . $conexion->error;
            }
        }

        // Si se ingresó el ID de la reserva
        if (!empty($id_reservas)) {
            // Preparar la consulta SQL para borrar la reserva
            $query_reservas = "DELETE FROM reservas WHERE id_reservas = '$id_reservas'";
            if ($conexion->query($query_reservas)) {
                if ($conexion->affected_rows > 0) {
                    echo "Reserva borrada correctamente.";
                } else {
                    echo "No se encontró ninguna reserva con ese ID.";
                }
            } else {
                echo "Error al borrar la reserva: " . $conexion->error;
            }
        }

        // Mostrar mensaje si no se ingresó ningún ID
        if (empty($id_cliente) && empty($id_reservas)) {
            echo "Por favor, ingrese un ID de cliente o de reserva.";
        }
    }

    // Mostrar la tabla de clientes
    $query_clientes = "SELECT id, nombre, numero_de_telefono FROM clientes";
    $resultado_clientes = $conexion->query($query_clientes);

    if ($resultado_clientes->num_rows > 0) {
        echo "<h2>Tabla de Clientes</h2>";
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Nombre</th><th>Número de Teléfono</th></tr>";

        while ($fila_cliente = $resultado_clientes->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $fila_cliente['id'] . "</td>";
            echo "<td>" . $fila_cliente['nombre'] . "</td>";
            echo "<td>" . $fila_cliente['numero_de_telefono'] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No hay clientes registrados.";
    }

    // Mostrar la tabla de reservas
    $query_reservas = "SELECT id_reservas, cliente, viaje, precio, fecha FROM reservas";
    $resultado_reservas = $conexion->query($query_reservas);

    if ($resultado_reservas->num_rows > 0) {
        echo "<h2>Tabla de Reservas</h2>";
        echo "<table border='1'>";
        echo "<tr><th>ID Reservas</th><th>Cliente</th><th>Viaje</th><th>Precio</th><th>Fecha</th></tr>";

        while ($fila_reservas = $resultado_reservas->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $fila_reservas['id_reservas'] . "</td>";
            echo "<td>" . $fila_reservas['cliente'] . "</td>";
            echo "<td>" . $fila_reservas['viaje'] . "</td>";
            echo "<td>" . $fila_reservas['precio'] . "</td>";
            echo "<td>" . $fila_reservas['fecha'] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No hay reservas registradas.";
    }

    $conexion->close();
    ?>

    <!-- Formulario único -->
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <h3>Borrar Cliente o Reserva</h3>
        <label for="id_cliente">ID del Cliente a Borrar (opcional):</label>
        <input type="text" id="id_cliente" name="id_cliente"><br><br>

        <label for="id_reservas">ID de la Reserva a Borrar:</label>
        <input type="text" id="id_reservas" name="id_reservas"><br><br>

        <input type="submit" value="Borrar">
    </form>

    <br>
    <button onclick="window.history.back()">Regresar a la página anterior</button>
</body>
</html>
