<?php
session_start();
require_once '../database/database.php';
require_once '../utils/helper.php';


$conn = connection();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../book.php");
    exit;
}

$fullname  = trim($_POST['fullname'] ?? '');
$number    = trim($_POST['number'] ?? '');
$checkin   = $_POST['check-in-date'] ?? '';
$checkout  = $_POST['check-out-date'] ?? '';
$facility  = $_POST['facility'] ?? '';
$request   = trim($_POST['request'] ?? '');
$payment_amount = $_POST['amount'] ?? 0;
$payment_status = $_POST['payment'] ?? 'Unpaid';

// --- Validation ---
$errors = [];

if (empty($fullname)) {
    $errors[] = 'Full name is required.';
}

$cleanNumber = str_replace(' ', '', $number);

if (
    empty($cleanNumber) ||
    !ctype_digit($cleanNumber) ||
    strlen($cleanNumber) < 10 ||
    strlen($cleanNumber) > 11
) {
    $errors[] = 'Please enter a valid phone number.';
}

if (empty($checkin)) {
    $errors[] = 'Check-in date is required.';
}

if (empty($checkout)) {
    $errors[] = 'Check-out date is required.';
}

if (!empty($checkin) && strtotime($checkin) < strtotime(date('Y-m-d'))) {
    $errors[] = 'Check-in date cannot be in the past.';
}

if (!empty($checkin) && !empty($checkout) && strtotime($checkout) <= strtotime($checkin)) {
    $errors[] = 'Check-out date must be after check-in date.';
}

if (empty($facility)) {
    $errors[] = 'Please select an accommodation.';
}

if (!empty($errors)) {
    flash('err', $errors);
    header('Location: ../book.php');
    exit;
}

$stmt = $conn->prepare("SELECT id FROM facilities WHERE id = ?");
$stmt->bind_param("i", $facility);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();

$facility_id = $res['id'] ?? null;

if (!$facility_id) {
    die("Invalid facility selected.");
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
$conflict = $stmt->get_result()->fetch_assoc();

if ($conflict['cnt'] > 0) {
    flash('err', ['Selected dates are already occupied for this facility.']);
    header('Location: ../book.php');
    exit;
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

flash('ok', ['Reservation request sent successfully!']);
header('Location: ../book.php');
exit;
