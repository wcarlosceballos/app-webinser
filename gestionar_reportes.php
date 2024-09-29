<?php
session_start(); // Inicia la sesión
if (!isset($_SESSION['username'])) {
    header('Location: autenticate.php'); // Redirige si no hay sesión iniciada
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú de Reportes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
</head>
<body>
    <nav>
        <div class="nav-wrapper">
            <a href="#" class="brand-logo">IE El Remolino</a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a href="admin_dashboard.php">Inicio</a></li>
                <li><a href="#!">Usuario: <?= $_SESSION['username'] ?></a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h3>Menú de Reportes</h3>

        <h5>Reportes</h5>
        <ul class="collection">
            <li class="collection-item"><a href="reporte_estudiante.php">Reporte por Estudiante</a></li>
            <li class="collection-item"><a href="reporte_cursos.php">Reporte por Curso</a></li>
        </ul>

        <h5>Estadísticas</h5>
        <ul class="collection">
            <li class="collection-item"><a href="estadisticas.php">Estadísticas de Estudiantes</a></li>
        </ul>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
