<?php
require_once '../database/database.php';

$conn = connection();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../book.php");
    exit;
}

$fullname = trim($_POST['fullname'] ?? '');
$number   = trim($_POST['number'] ?? '');
$facility = $_POST['facility'] ?? '';
$checkin  = $_POST['check-in-date'] ?? '';
$checkout = $_POST['check-out-date'] ?? '';
$request  = trim($_POST['request'] ?? '');
$payment_amount = $_POST['amount'] ?? 0;
$payment_status = $_POST['payment'] ?? 'Unpaid';

if ($fullname === '' || $number === '' || $facility === '' || $checkin === '' || $checkout === '') {
    die("Missing required fields.");
}


$stmt = $conn->prepare("SELECT id FROM facilities WHERE name = ?");
$stmt->bind_param("s", $facility);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();

$facility_id = $res['id'] ?? null;

if (!$facility_id) {
    die("Invalid facility selected.");
}

$stmt = $conn->prepare("
    INSERT INTO bookings
    (guest_name, phone_number, facility_id, date_start, date_end, notes, status, payment_amount, payment_status)
    VALUES (?, ?, ?, ?, ?, ?, 'Pending', ?, ?)
");

$stmt->bind_param(
    "ssisssds",
    $fullname,
    $number,
    $facility_id,
    $checkin,
    $checkout,
    $request,
    $payment_amount,
    $payment_status
);

$stmt->execute();

header("Location: ../book.php?success=1");
exit;