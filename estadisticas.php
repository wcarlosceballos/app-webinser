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

// Obtener estadísticas
$promedio_general_estudiantes = "SELECT id_estudiante, AVG(nota) AS promedio FROM notas GROUP BY id_estudiante";
$promedio_general_result = $conn->query($promedio_general_estudiantes);

$promedio_por_grado = "SELECT e.grado, AVG(n.nota) AS promedio_grado 
                        FROM notas n 
                        JOIN estudiantes e ON n.id_estudiante = e.id_estudiante 
                        GROUP BY e.grado";
$promedio_grado_result = $conn->query($promedio_por_grado);

$estadisticas_bajas_por_grado = "SELECT e.grado, COUNT(*) AS estudiantes_perdidos 
                                  FROM notas n 
                                  JOIN estudiantes e ON n.id_estudiante = e.id_estudiante 
                                  WHERE n.nota < 3 
                                  GROUP BY e.grado";
$estadisticas_bajas_result = $conn->query($estadisticas_bajas_por_grado);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estadísticas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
</head>
<body>
<nav>
    <div class="nav-wrapper blue">
        <a href="#" class="brand-logo">Gestión de Notas</a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
            <li><a href="admin_dashboard.php">Inicio</a></li>
        </ul>
    </div>
</nav>

<div class="container">
    <h3>Estadísticas</h3>

    <ul class="tabs">
        <li class="tab col s3"><a href="#promedio_general">Promedio General Estudiantes</a></li>
        <li class="tab col s3"><a href="#promedio_grado">Promedio por Grado</a></li>
        <li class="tab col s3"><a href="#bajas_grado">Estudiantes con Bajo Rendimiento</a></li>
    </ul>

    <div id="promedio_general" class="col s12">
        <h4>Promedio General por Estudiante</h4>
        <table class="striped">
            <thead>
                <tr>
                    <th>ID Estudiante</th>
                    <th>Promedio</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($promedio = $promedio_general_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $promedio['id_estudiante']; ?></td>
                        <td><?php echo round($promedio['promedio'], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div id="promedio_grado" class="col s12">
        <h4>Promedio por Grado</h4>
        <table class="striped">
            <thead>
                <tr>
                    <th>Grado</th>
                    <th>Promedio</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($grado = $promedio_grado_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $grado['grado']; ?></td>
                        <td><?php echo round($grado['promedio_grado'], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div id="bajas_grado" class="col s12">
        <h4>Estudiantes con Bajo Rendimiento (Nota < 3)</h4>
        <table class="striped">
            <thead>
                <tr>
                    <th>Grado</th>
                    <th>Estudiantes Perdidos</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($bajas = $estadisticas_bajas_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $bajas['grado']; ?></td>
                        <td><?php echo $bajas['estudiantes_perdidos']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('.tabs');
        var instances = M.Tabs.init(elems);
    });
</script>
</body>
</html>

<?php
$conn->close();
?>
