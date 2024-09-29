<?php
// Conexión a la base de datos
require_once 'config.php';

// Inicializar variables
$nombre = $apellido = $especialidad = $error = "";
$success = "";

// Función para prevenir duplicidad del ID docente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['save'])) {
        $id_docente = $_POST['id_docente'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $especialidad = $_POST['especialidad'];

        // Validar que no haya duplicidad en el ID
        $stmt = $pdo->prepare("SELECT * FROM docentes WHERE id_docente = :id_docente");
        $stmt->execute(['id_docente' => $id_docente]);
        if ($stmt->rowCount() > 0) {
            $error = "El ID de docente ya existe.";
        } else {
            // Insertar nuevo docente
            $stmt = $pdo->prepare("INSERT INTO docentes (id_docente, nombre, apellido, especialidad) VALUES (:id_docente, :nombre, :apellido, :especialidad)");
            $stmt->execute(['id_docente' => $id_docente, 'nombre' => $nombre, 'apellido' => $apellido, 'especialidad' => $especialidad]);
            $success = "Docente guardado correctamente.";
        }
    }

    // Función para eliminar un docente
    if (isset($_GET['delete'])) {
        $id_docente = $_GET['delete'];
        $stmt = $pdo->prepare("DELETE FROM docentes WHERE id_docente = :id_docente");
        $stmt->execute(['id_docente' => $id_docente]);
        $success = "Docente eliminado correctamente.";
    }

    // Función para actualizar un docente
    if (isset($_POST['update'])) {
        $id_docente = $_POST['id_docente'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $especialidad = $_POST['especialidad'];

        $stmt = $pdo->prepare("UPDATE docentes SET nombre = :nombre, apellido = :apellido, especialidad = :especialidad WHERE id_docente = :id_docente");
        $stmt->execute(['id_docente' => $id_docente, 'nombre' => $nombre, 'apellido' => $apellido, 'especialidad' => $especialidad]);
        $success = "Docente actualizado correctamente.";
    }
}

// Obtener todos los docentes para mostrar en la tabla
$stmt = $pdo->query("SELECT * FROM docentes ORDER BY id_docente");
$docentes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Docentes</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Gestión de Docentes</h2>
        <a href="dashboard.php" class="btn blue">Regresar al Dashboard</a>

        <?php if ($error): ?>
            <div class="card-panel red lighten-2"><?= $error ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="card-panel green lighten-2"><?= $success ?></div>
        <?php endif; ?>

        <!-- Formulario de creación/actualización de docentes -->
        <form action="docentes.php" method="POST">
            <div class="row">
                <div class="input-field col s12">
                    <input type="number" name="id_docente" required>
                    <label for="id_docente">ID del Docente</label>
                </div>
                <div class="input-field col s12">
                    <input type="text" name="nombre" required>
                    <label for="nombre">Nombre</label>
                </div>
                <div class="input-field col s12">
                    <input type="text" name="apellido" required>
                    <label for="apellido">Apellido</label>
                </div>
                <div class="input-field col s12">
                    <input type="text" name="especialidad" required>
                    <label for="especialidad">Especialidad</label>
                </div>
                <div class="input-field col s12">
                    <button type="submit" name="save" class="btn green">Guardar Docente</button>
                    <button type="submit" name="update" class="btn yellow">Actualizar Docente</button>
                </div>
            </div>
        </form>

        <!-- Tabla de docentes -->
        <table class="striped">
            <thead>
                <tr>
                    <th>ID Docente</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Especialidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($docentes as $docente): ?>
                    <tr>
                        <td><?= $docente['id_docente'] ?></td>
                        <td><?= $docente['nombre'] ?></td>
                        <td><?= $docente['apellido'] ?></td>
                        <td><?= $docente['especialidad'] ?></td>
                        <td>
                            <a href="docentes.php?delete=<?= $docente['id_docente'] ?>" class="btn red">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
