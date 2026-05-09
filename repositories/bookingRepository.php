<!-- dito kayo magbackend sa lahat ng may kinalaman booking  -->
<!-- ilagay nyo nalang sa action attribute yung file path neto -->

<?php
require_once '../database/database.php';

$conn = connection();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../book.php");
    exit;
}

$guest_name = trim($_POST['guest_name']);
$phone_number = trim($_POST['phone_number']);
$facility_id = (int)$_POST['facility'];
$date_start = $_POST['date_start'];
$date_end = $_POST['date_end'];
$notes = $_POST['request'] ?? null;

$stmt = $conn->prepare("
    INSERT INTO bookings
    (guest_name, phone_number, facility_id, date_start, date_end, status, payment_status, payment_amount, notes)
    VALUES (?, ?, ?, ?, ?, 'Pending', 'Unpaid', 0, ?)
");

$stmt->bind_param(
    "ssisss",
    $guest_name,
    $phone_number,
    $facility_id,
    $date_start,
    $date_end,
    $notes
);

$stmt->execute();

header("Location: ../book.php");
exit;