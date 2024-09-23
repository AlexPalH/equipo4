<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar reservas</title>
    <link rel="stylesheet" href="estilomodificar.css">
</head>
<body>
    <h2>Lista de reservas</h2>
    <?php
    // Detalles de la base de datos
    $server = "localhost";
    $username = "root";
    $password = "";
    $db = "agencia_viajes";

    // Crear una nueva conexión a MySQL
    $conexion = new mysqli($server, $username, $password, $db);

    // Verificar la conexión
    if($conexion->connect_errno) {
        die("Conexión fallida: " . $conexion->connect_errno . " - " . $conexion->connect_error);
    }

    // Procesar la modificación del cliente
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modificar_cliente'])) {
        $id = mysqli_real_escape_string($conexion, $_POST['id']);
        $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
        $direccion = mysqli_real_escape_string($conexion, $_POST['direccion']);
        $numero_de_telefono = mysqli_real_escape_string($conexion, $_POST['numero_de_telefono']);

        $query = "UPDATE clientes SET nombre = '$nombre', direccion = '$direccion', numero_de_telefono = '$numero_de_telefono' WHERE id = '$id'";
        if ($conexion->query($query)) {
            echo "Cliente actualizado correctamente.";
        } else {
            echo "Error al actualizar el cliente: " . $conexion->error;
        }
    }

    // Procesar la solicitud para editar un cliente
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editar_cliente'])) {
        $id = mysqli_real_escape_string($conexion, $_POST['id']);

        $query = "SELECT id, nombre, direccion, numero_de_telefono FROM clientes WHERE id = '$id'";
        $resultado = $conexion->query($query);

        if ($resultado && $resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();
    ?>
    <h2>Modificar viaje</h2>
    <form method="post" action="">
        <input type="hidden" name="id" value="<?php echo $fila['id']; ?>">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo $fila['nombre']; ?>" required><br><br>
        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" value="<?php echo $fila['direccion']; ?>" required><br><br>
        <label for="numero_de_telefono">Número de Teléfono:</label>
        <input type="text" id="numero_de_telefono" name="numero_de_telefono" value="<?php echo $fila['numero_de_telefono']; ?>" required><br><br>
        <input type="submit" name="modificar_cliente" value="Actualizar Cliente">
    </form>
    <?php
        } else {
            echo "Cliente no encontrado.";
        }
    }

    // Consulta para obtener todos los clientes
    $query_clientes = "SELECT id, nombre, direccion, numero_de_telefono FROM clientes";
    $resultado_clientes = $conexion->query($query_clientes);

    if ($resultado_clientes->num_rows > 0) {
        // Mostrar los resultados de clientes en una tabla HTML
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Nombre</th><th>Dirección</th><th>Número de Teléfono</th><th>Acción</th></tr>";

        while ($fila_cliente = $resultado_clientes->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $fila_cliente['id'] . "</td>";
            echo "<td>" . $fila_cliente['nombre'] . "</td>";
            echo "<td>" . $fila_cliente['direccion'] . "</td>";
            echo "<td>" . $fila_cliente['numero_de_telefono'] . "</td>";
            echo "<td>";
            echo "<form method='post' action='' style='display:inline;'>";
            echo "<input type='hidden' name='id' value='" . $fila_cliente['id'] . "'>";
            echo "<input type='submit' name='editar_cliente' value='Modificar'>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No se encontraron clientes.";
    }

    // Procesar la modificación de la reserva
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modificar_reserva'])) {
        $id_reservas = mysqli_real_escape_string($conexion, $_POST['id_reservas']);
        $viaje = mysqli_real_escape_string($conexion, $_POST['viaje']);
        $precio = mysqli_real_escape_string($conexion, $_POST['precio']);
        $fecha = mysqli_real_escape_string($conexion, $_POST['fecha']);

        $query = "UPDATE reservas SET viaje = '$viaje', precio = '$precio', fecha = '$fecha' WHERE id_reservas = '$id_reservas'";
        if ($conexion->query($query)) {
            echo "Reserva actualizada correctamente.";
        } else {
            echo "Error al actualizar la reserva: " . $conexion->error;
        }
    }

    // Procesar la solicitud para editar una reserva
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editar_reserva'])) {
        $id_reservas = mysqli_real_escape_string($conexion, $_POST['id_reservas']);

        $query = "SELECT id_reservas, cliente, viaje, precio, fecha FROM reservas WHERE id_reservas = '$id_reservas'";
        $resultado = $conexion->query($query);

        if ($resultado && $resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();
    ?>
    <h2>Modificar Reserva</h2>
    <form method="post" action="">
        <input type="hidden" name="id_reservas" value="<?php echo $fila['id_reservas']; ?>">
        <label for="viaje">Viaje:</label>
        <input type="text" id="viaje" name="viaje" value="<?php echo $fila['viaje']; ?>" required><br><br>
        <label for="precio">Precio:</label>
        <input type="text" id="precio" name="precio" value="<?php echo $fila['precio']; ?>" required><br><br>
        <label for="fecha">Fecha:</label>
        <input type="text" id="fecha" name="fecha" value="<?php echo $fila['fecha']; ?>" required><br><br>
        <input type="submit" name="modificar_reserva" value="Actualizar Reserva">
    </form>
    <?php
        } else {
            echo "Reserva no encontrada.";
        }
    }

    // Consulta para obtener todas las reservas
    $query_reservas = "SELECT id_reservas, cliente, viaje, precio, fecha FROM reservas";
    $resultado_reservas = $conexion->query($query_reservas);

    if ($resultado_reservas->num_rows > 0) {
        // Mostrar los resultados de reservas en una tabla HTML
        echo "<h2>Lista de Reservas</h2>";
        echo "<table border='1'>";
        echo "<tr><th>ID Reservas</th><th>Cliente</th><th>Viaje</th><th>Precio</th><th>Fecha</th><th>Acción</th></tr>";

        while ($fila_reserva = $resultado_reservas->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $fila_reserva['id_reservas'] . "</td>";
            echo "<td>" . $fila_reserva['cliente'] . "</td>";
            echo "<td>" . $fila_reserva['viaje'] . "</td>";
            echo "<td>" . $fila_reserva['precio'] . "</td>";
            echo "<td>" . $fila_reserva['fecha'] . "</td>";
            echo "<td>";
            echo "<form method='post' action='' style='display:inline;'>";
            echo "<input type='hidden' name='id_reservas' value='" . $fila_reserva['id_reservas'] . "'>";
            echo "<input type='submit' name='editar_reserva' value='Modificar'>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No se encontraron reservas.";
    }

    $conexion->close();
    ?>

<br>
    <button onclick="window.history.back()">Regresar a la página anterior</button>
</body>
</html>
