<?php
session_start();
require_once '../utils/helper.php';
require_once '../database/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../admin/bookings.php');
    exit;
}

$conn = connection();
$action     = $_POST['action'] ?? 'save';
$booking_id = isset($_POST['booking_id']) ? (int)$_POST['booking_id'] : null;

// --- Delete ---
if ($action === 'delete' && $booking_id) {
    $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    flash('ok', ['Booking deleted successfully.']);
    header('Location: ../admin/bookings.php');
    exit;
}

// --- Collect inputs ---
$name           = trim($_POST['name'] ?? '');
$phone_number   = trim($_POST['phone_number'] ?? '');
$facility       = $_POST['facility'] ?? '';
$start_date     = $_POST['start_date'] ?? '';
$end_date       = $_POST['end_date'] ?? '';
$status         = $_POST['status'] ?? '';
$payment        = $_POST['payment'] ?? '';
$amount         = $_POST['amount'] ?? '';
$notes          = trim($_POST['notes'] ?? '');

// --- Validation ---
$errors = [];

if (empty($name)) {
    $errors[] = 'Guest name is required.';
}

$cleanNumber = str_replace(' ', '', $phone_number);
if (empty($cleanNumber) || !ctype_digit($cleanNumber) || strlen($cleanNumber) < 10 || strlen($cleanNumber) > 11) {
    $errors[] = 'Please enter a valid phone number.';
}

if (empty($facility)) {
    $errors[] = 'Please select a facility.';
}

if (empty($start_date)) {
    $errors[] = 'Start date is required.';
}

if (empty($end_date)) {
    $errors[] = 'End date is required.';
}

if (!$booking_id && !empty($start_date) && strtotime($start_date) < strtotime(date('Y-m-d'))) {
    $errors[] = 'Start date cannot be in the past.';
}

if (!empty($start_date) && !empty($end_date) && strtotime($end_date) <= strtotime($start_date)) {
    $errors[] = 'End date must be after start date.';
}

if (empty($status)) {
    $errors[] = 'Status is required.';
}

if (empty($payment)) {
    $errors[] = 'Payment status is required.';
}

if ($amount === '' || !is_numeric($amount) || (float)$amount < 0) {
    $errors[] = 'Please enter a valid amount.';
}

$redirectBack = $booking_id
    ? '../admin/bookings.php?edit=' . $booking_id
    : '../admin/bookings.php';

if (!empty($errors)) {
    flash('err', $errors);
    header('Location: ' . $redirectBack);
    exit;
}

$amount = (float)$amount;

// --- Update ---
if ($booking_id) {
    $stmt = $conn->prepare("
        UPDATE bookings
        SET guest_name = ?, phone_number = ?, facility_id = ?, date_start = ?,
            date_end = ?, status = ?, payment_status = ?, payment_amount = ?, notes = ?
        WHERE id = ?
    ");
    $stmt->bind_param("ssissssdii", $name, $phone_number, $facility, $start_date, $end_date, $status, $payment, $amount, $notes, $booking_id);
    $stmt->execute();
    flash('ok', ['Booking updated successfully.']);

// --- Insert ---
} else {
    $stmt = $conn->prepare("
        INSERT INTO bookings
        (guest_name, phone_number, facility_id, date_start, date_end, status, payment_status, payment_amount, notes)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("ssisssds", $name, $phone_number, $facility, $start_date, $end_date, $status, $payment, $amount, $notes);
    $stmt->execute();
    flash('ok', ['Booking created successfully.']);
}

header('Location: ../admin/bookings.php');
exit;
