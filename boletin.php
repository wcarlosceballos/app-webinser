<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: autenticate.php');
    exit();
}

require 'db_connection.php';
require_once('tcpdf/tcpdf.php'); // Asegúrate de ajustar la ruta según tu estructura de directorios

// Verifica si se ha enviado un ID de estudiante
if (isset($_GET['estudiante_id'])) {
    $estudiante_id = $_GET['estudiante_id'];

    // Consulta para obtener las notas del estudiante
    $sql = "SELECT asignaturas.nombre AS asignatura, logros.nota 
            FROM logros 
            JOIN asignaturas ON logros.asignatura_id = asignaturas.id 
            WHERE logros.estudiante_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$estudiante_id]);
    $notas = $stmt->fetchAll();

    // Datos del estudiante (opcional)
    $sql_estudiante = "SELECT nombre, apellido FROM estudiantes WHERE id = ?";
    $stmt_estudiante = $conn->prepare($sql_estudiante);
    $stmt_estudiante->execute([$estudiante_id]);
    $estudiante = $stmt_estudiante->fetch();
    
    // Crea el objeto PDF
    $pdf = new TCPDF();
    $pdf->AddPage();

    // Establece el título del documento
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'Reporte de Notas de ' . $estudiante['nombre'] . ' ' . $estudiante['apellido'], 0, 1, 'C');

    // Crea la tabla
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(60, 10, 'Asignatura', 1);
    $pdf->Cell(60, 10, 'Nota', 1);
    $pdf->Ln();

    foreach ($notas as $nota) {
        $pdf->Cell(60, 10, $nota['asignatura'], 1);
        $pdf->Cell(60, 10, $nota['nota'], 1);
        $pdf->Ln();
    }

    // Salida del PDF
    $pdf->Output('reporte_notas_' . $estudiante['nombre'] . '_' . $estudiante['apellido'] . '.pdf', 'I');
} else {
    echo "ID de estudiante no proporcionado.";
}
?>
