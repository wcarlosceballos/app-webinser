<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion_notas";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los grados disponibles
$gradosQuery = "SELECT DISTINCT grado FROM estudiantes";
$gradosResult = $conn->query($gradosQuery);

// Manejo del formulario
$gradoSeleccionado = isset($_POST['grado']) ? $_POST['grado'] : null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Notas por Curso</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
</head>
<body>

<nav>
    <div class="nav-wrapper">
        <a href="#" class="brand-logo">IE El Remolino</a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
            <li><a href="admin_dashboard.php">Inicio</a></li>
        </ul>
    </div>
</nav>

<div class="container">
    <h1>Reporte de Notas por Curso</h1>
    
    <form method="POST" action="">
        <div class="input-field">
            <select name="grado" required>
                <option value="" disabled selected>Seleccionar Grado</option>
                <?php while ($grado = $gradosResult->fetch_assoc()): ?>
                    <option value="<?php echo $grado['grado']; ?>" <?php echo $gradoSeleccionado == $grado['grado'] ? 'selected' : ''; ?>>
                        <?php echo $grado['grado']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <label>Grado</label>
        </div>
        <button type="submit" class="btn">Ver Reporte</button>
    </form>

    <?php if ($gradoSeleccionado): ?>
        <h2>Notas del Grado <?php echo $gradoSeleccionado; ?></h2>
        <table class="striped">
            <thead>
                <tr>
                    <th>Estudiante</th>
                    <th>Asignatura</th>
                    <th>Periodo</th>
                    <th>Nota</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Obtener notas por grado
                $notasQuery = "SELECT CONCAT(e.nombre, ' ', e.apellido) AS estudiante, 
                                      a.nombre AS nombre_asignatura, 
                                      n.periodo, 
                                      n.nota 
                               FROM notas n
                               JOIN estudiantes e ON n.id_estudiante = e.id_estudiante
                               JOIN asignaturas a ON n.id_asignatura = a.id_asignatura
                               WHERE e.grado = '$gradoSeleccionado'";
                $notasResult = $conn->query($notasQuery);

                if ($notasResult->num_rows > 0) {
                    while ($nota = $notasResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $nota['estudiante']; ?></td>
                            <td><?php echo $nota['nombre_asignatura']; ?></td>
                            <td><?php echo $nota['periodo']; ?></td>
                            <td><?php echo $nota['nota']; ?></td>
                        </tr>
                    <?php endwhile;
                } else {
                    echo '<tr><td colspan="4">No hay notas registradas para este grado.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('select');
        var instances = M.FormSelect.init(elems);
    });
</script>
</body>
</html>

<?php
$conn->close();
?>
