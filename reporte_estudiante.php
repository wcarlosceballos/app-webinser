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

// Obtener los estudiantes
$estudiantesQuery = "SELECT id_estudiante, nombre, apellido FROM estudiantes";
$estudiantesResult = $conn->query($estudiantesQuery);

$id_estudiante_seleccionado = null;
$estudiante_nombre = "";
$notasResult = null;

if (isset($_GET['id_estudiante'])) {
    $id_estudiante_seleccionado = $_GET['id_estudiante'];

    // Obtener nombre completo del estudiante
    $nombreEstudianteQuery = "SELECT nombre, apellido FROM estudiantes WHERE id_estudiante = ?";
    $stmt = $conn->prepare($nombreEstudianteQuery);
    $stmt->bind_param("i", $id_estudiante_seleccionado);
    $stmt->execute();
    $nombreEstudianteResult = $stmt->get_result();
    
    if ($nombreEstudianteResult->num_rows > 0) {
        $estudiante = $nombreEstudianteResult->fetch_assoc();
        $estudiante_nombre = $estudiante['nombre'] . ' ' . $estudiante['apellido'];
    }

    // Obtener las notas del estudiante seleccionado
    $notasQuery = "
        SELECT a.nombre AS nombre_asignatura, n.periodo, n.nota 
        FROM notas n
        JOIN asignaturas a ON n.id_asignatura = a.id_asignatura
        WHERE n.id_estudiante = ?";
    $stmt = $conn->prepare($notasQuery);
    $stmt->bind_param("i", $id_estudiante_seleccionado);
    $stmt->execute();
    $notasResult = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Notas por Estudiante</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
</head>
<body>
<nav>
    <div class="nav-wrapper">
        <a href="#" class="brand-logo">Gestión de Notas</a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
            <li><a href="admin_dashboard.php">Inicio</a></li>
            <li><a href="estadisticas.php">Estadísticas</a></li>
            <li><a href="reporte_curso.php">Reporte por Curso</a></li>
        </ul>
    </div>
</nav>

<div class="container">
    <h1>Reporte de Notas por Estudiante</h1>

    <!-- Filtro de estudiantes -->
    <form action="" method="GET">
        <label for="id_estudiante">Seleccionar Estudiante:</label>
        <select name="id_estudiante" onchange="this.form.submit()">
            <option value="">Seleccionar...</option>
            <?php while ($estudiante = $estudiantesResult->fetch_assoc()): ?>
                <option value="<?php echo $estudiante['id_estudiante']; ?>"
                    <?php echo ($id_estudiante_seleccionado == $estudiante['id_estudiante']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>

    <?php if ($id_estudiante_seleccionado): ?>
        <h2>Notas de <?php echo htmlspecialchars($estudiante_nombre); ?></h2>
        
        <table class="striped">
            <thead>
                <tr>
                    <th>Asignatura</th>
                    <th>Periodo</th>
                    <th>Nota</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($notasResult && $notasResult->num_rows > 0): ?>
                    <?php while ($nota = $notasResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($nota['nombre_asignatura']); ?></td>
                            <td><?php echo htmlspecialchars($nota['periodo']); ?></td>
                            <td><?php echo htmlspecialchars($nota['nota']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No se encontraron notas para este estudiante.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <!-- Botón para imprimir -->
        <button class="btn blue" onclick="window.print()">Imprimir Reporte</button>
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
