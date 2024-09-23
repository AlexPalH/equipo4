<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Nuevo Cliente y Nueva Reserva</title>
    <link rel="stylesheet" href="estiloInsertar.css">
    <script>
        function actualizarPrecio() {
            const precios = {
                'Tepito': 800,
                'Iguala': 500,
                'Ometepec': 400,
                'Pitorreal': 300,
                'Tangamandapio': 100,
                'Salsipuedes': 100,
                'Colula': 300,
                'Veracruz': 0
            };
            
            const viajeSeleccionado = document.getElementById('viaje').value;
            document.getElementById('precio').value = precios[viajeSeleccionado];
            actualizarFechas(viajeSeleccionado);
        }

        function actualizarFechas(viaje) {
            const fechasDisponibles = {
                'Tepito': ['2024-10-01', '2024-10-05', '2024-10-10'],
                'Iguala': ['2024-10-02', '2024-10-06', '2024-10-11'],
                'Ometepec': ['2024-10-03', '2024-10-07', '2024-10-12'],
                'Pitorreal': ['2024-10-04', '2024-10-08', '2024-10-13'],
                'Tangamandapio': ['2024-10-05', '2024-10-09', '2024-10-14'],
                'Salsipuedes': ['2024-10-06', '2024-10-10', '2024-10-15'],
                'Colula': ['2024-10-07', '2024-10-11', '2024-10-16'],
                'Veracruz': ['2024-10-08', '2024-10-12', '2024-10-17']
            };

            const fechasSelect = document.getElementById('fecha');
            fechasSelect.innerHTML = ''; // Limpiar las fechas anteriores

            fechasDisponibles[viaje].forEach(function(fecha) {
                const option = document.createElement('option');
                option.value = fecha;
                option.textContent = fecha;
                fechasSelect.appendChild(option);
            });
        }
    </script>
</head>
<body>
    <h2>Formulario de Nuevo Cliente y Nueva Reserva</h2>

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

    // Función para limpiar y verificar entradas
    function sanitize_input($conexion, $input) {
        return empty($input) ? "NULL" : "'" . mysqli_real_escape_string($conexion, $input) . "'";
    }

    // Procesamiento del formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = sanitize_input($conexion, $_POST['nombre']);
        $direccion = sanitize_input($conexion, $_POST['direccion']);
        $numero_de_telefono = sanitize_input($conexion, $_POST['numero_de_telefono']);
        $viaje = sanitize_input($conexion, $_POST['viaje']);
        $precio = sanitize_input($conexion, $_POST['precio']);
        $fecha = sanitize_input($conexion, $_POST['fecha']);

        // Crear cliente
        $query_cliente = "INSERT INTO clientes (nombre, direccion, numero_de_telefono) VALUES ($nombre, $direccion, $numero_de_telefono)";
        
        if ($conexion->query($query_cliente)) {
            // Obtener el ID del cliente recién creado
            $cliente_id = $conexion->insert_id;

            // Crear reserva usando el ID del cliente
            $query_reserva = "INSERT INTO reservas (cliente, viaje, precio, fecha) VALUES ($cliente_id, $viaje, $precio, $fecha)";
            if ($conexion->query($query_reserva)) {
                echo "Cliente y reserva creados correctamente.";
            } else {
                echo "Error al crear reserva: " . $conexion->error;
            }
        } else {
            echo "Error al crear cliente: " . $conexion->error;
        }
    }

    $conexion->close();
    ?>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <h3>Nuevo Cliente</h3>
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br><br>

        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" required><br><br>

        <label for="numero_de_telefono">Número de Teléfono:</label>
        <input type="text" id="numero_de_telefono" name="numero_de_telefono" required><br><br>

        <h3>Nueva Reserva</h3>
        <label for="viaje">Viaje:</label>
        <select id="viaje" name="viaje" onchange="actualizarPrecio()">
        <option value="Seleccion">Seleccione un viaje</option>
            <option value="Tepito">Tepito</option>
            <option value="Iguala">Iguala</option>
            <option value="Ometepec">Ometepec</option>
            <option value="Pitorreal">Pitorreal</option>
            <option value="Tangamandapio">Tangamandapio</option>
            <option value="Salsipuedes">Salsipuedes</option>
            <option value="Colula">Colula</option>
            <option value="Veracruz">Veracruz</option>
        </select><br><br>

        <label for="precio">Precio:</label>
        <input type="text" id="precio" name="precio" readonly><br><br>

        <label for="fecha">Fechas disponibles:</label>
        <select id="fecha" name="fecha" required>
            <option value="">Seleccione una fecha</option>
        </select><br><br>

        <input type="submit" value="Guardar Cliente y Reserva">
    </form>

    <br>
    <button onclick="window.history.back()">Regresar a la página anterior</button>
</body>
</html>
