<?php
// Script para verificar una reserva específica
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/models/Database.php';

$reserva_id = isset($_GET['id']) ? (int)$_GET['id'] : 15;

$db = Database::getInstance()->getConnection();

// Obtener reserva por ID
$sql = "SELECT * FROM reservas WHERE id_reserva = :id";
$stmt = $db->prepare($sql);
$stmt->execute([':id' => $reserva_id]);
$reserva = $stmt->fetch(PDO::FETCH_ASSOC);

echo "<h1>Datos de la Reserva ID: {$reserva_id}</h1>";

if ($reserva) {
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
    foreach ($reserva as $campo => $valor) {
        echo "<tr>";
        echo "<td><strong>{$campo}</strong></td>";
        echo "<td>{$valor}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<br><br>";
    echo "<h2>URL Correcta para Pago:</h2>";
    echo "<p><a href='/sistemaVuelos/payment/checkout?booking={$reserva['codigo_reserva']}' style='font-size: 18px; color: blue;'>";
    echo "http://localhost/sistemaVuelos/payment/checkout?booking={$reserva['codigo_reserva']}";
    echo "</a></p>";
    
    echo "<br>";
    echo "<h3>Prueba hacer clic en el enlace de arriba para ir al pago</h3>";
} else {
    echo "<p style='color: red;'>No se encontró la reserva con ID {$reserva_id}</p>";
}
?>
