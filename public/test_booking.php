<?php
// Script de prueba para verificar reservas
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/models/Database.php';

$db = Database::getInstance()->getConnection();

// Obtener las últimas 5 reservas
$sql = "SELECT id_reserva, codigo_reserva, id_usuario, estado_reserva, fecha_reserva, monto_total 
        FROM reservas 
        ORDER BY id_reserva DESC 
        LIMIT 5";

$stmt = $db->query($sql);
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h1>Últimas 5 Reservas en la Base de Datos</h1>";
echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
echo "<tr style='background: #f0f0f0;'>";
echo "<th>ID</th><th>Código Reserva</th><th>Usuario ID</th><th>Estado</th><th>Fecha</th><th>Monto</th>";
echo "</tr>";

foreach ($reservas as $reserva) {
    echo "<tr>";
    echo "<td>{$reserva['id_reserva']}</td>";
    echo "<td><strong>{$reserva['codigo_reserva']}</strong></td>";
    echo "<td>{$reserva['id_usuario']}</td>";
    echo "<td>{$reserva['estado_reserva']}</td>";
    echo "<td>{$reserva['fecha_reserva']}</td>";
    echo "<td>{$reserva['monto_total']}</td>";
    echo "</tr>";
}

echo "</table>";

echo "<br><br>";
echo "<h2>Verificación:</h2>";
echo "<p>Si la columna 'Código Reserva' está vacía o muestra NULL, entonces el problema está en la creación de la reserva.</p>";
echo "<p>Si muestra un código como 'RV123456', entonces el problema está en cómo se recupera el dato.</p>";
?>
