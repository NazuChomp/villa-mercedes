<?php
require_once '../database/database.php';

$conn = connection();

$facility_id = $_GET['facility_id'] ?? null;
$checkin = $_GET['checkin'] ?? null;
$checkout = $_GET['checkout'] ?? null;

if (!$facility_id || !$checkin || !$checkout) {
    echo json_encode(['available' => false]);
    exit;
}

$stmt = $conn->prepare("
    SELECT COUNT(*) as cnt
    FROM bookings
    WHERE facility_id = ?
    AND status != 'Cancelled'
    AND (date_start <= ? AND date_end >= ?)
");

$stmt->bind_param("iss", $facility_id, $checkout, $checkin);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();

echo json_encode([
    'available' => $res['cnt'] == 0
]);