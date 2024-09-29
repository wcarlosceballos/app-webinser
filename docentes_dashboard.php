<?php
session_start();

// Verificar si el usuario es un docente
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Teacher') {
    header('Location: autenticate.php'); // Redirigir si no está autenticado
    exit();
}

require 'db_connection.php';

// Obtener los cursos y asignaturas asignados al docente
$docente_id = $_SESSION['user_id']; // Se asume que el docente ha iniciado sesión y se guarda el ID en la sesión
$sql = "SELECT asignaturas.id AS asignatura_id, asignaturas.nombre AS asignatura, cursos.nombre AS curso 
        FROM asignaturas
        JOIN cursos ON asignaturas.curso_id = cursos.id
        WHERE asignaturas.docente_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$docente_id]);
$asignaciones = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Docente</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
</head>
<body>
    <!-- Barra de navegación -->
    <nav>
        <div class="nav-wrapper">
            <a href="#" class="brand-logo">IE El Remolino</a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a href="teacher_dashboard.php">Inicio</a></li>
                <li><a href="#!">Usuario: <?= $_SESSION['username'] ?></a></li>
                <li><a href="logout.php" class="btn red">Cerrar Sesión</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h3>Bienvenido, <?= $_SESSION['username'] ?></h3>

        <!-- Listado de cursos y asignaturas del docente -->
        <h4>Asignaturas Asignadas</h4>
        <table class="striped">
            <thead>
                <tr>
                    <th>Curso</th>
                    <th>Asignatura</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($asignaciones as $asignacion): ?>
                    <tr>
                        <td><?= $asignacion['curso'] ?></td>
                        <td><?= $asignacion['asignatura'] ?></td>
                        <td>
                            <a href="calificar.php?asignatura_id=<?= $asignacion['asignatura_id'] ?>" class="btn-small">Calificar Estudiantes</a>
                            <a href="reporte_notas_asignatura.php?asignatura_id=<?= $asignacion['asignatura_id'] ?>" class="btn-small green">Ver Reporte</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Sección de estadísticas -->
        <h4>Estadísticas de Desempeño</h4>
        <a href="estadisticas.php" class="btn">Ver Estadísticas</a>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
