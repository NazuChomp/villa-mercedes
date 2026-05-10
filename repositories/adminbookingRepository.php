
<?php
require_once '../database/database.php';

$conn = connection();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../admin/bookings.php");
    exit;
}

$action = $_POST['action'] ?? null;


if ($action === 'delete') {

    $id = (int)$_POST['booking_id'];

    $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: ../admin/bookings.php");
    exit;
}


$id           = $_POST['booking_id'] ?? null;
$name         = trim($_POST['name']);
$phone        = trim($_POST['phone_number']);
$facility_id  = (int)$_POST['facility'];
$start        = $_POST['start_date'];
$end          = $_POST['end_date'];
$status       = $_POST['status'];
$payment      = $_POST['payment'];
$amount       = (float)$_POST['amount'];
$notes        = trim($_POST['notes']);

if ($id) {

    $stmt = $conn->prepare("
        UPDATE bookings
        SET guest_name=?, phone_number=?, facility_id=?,
            date_start=?, date_end=?,
            status=?, payment_status=?, payment_amount=?, notes=?
        WHERE id=?
    ");

    $stmt->bind_param(
        "ssissssdsi",
        $name,
        $phone,
        $facility_id,
        $start,
        $end,
        $status,
        $payment,
        $amount,
        $notes,
        $id
    );

} else {

    $stmt = $conn->prepare("
        INSERT INTO bookings
        (guest_name, phone_number, facility_id,
         date_start, date_end,
         status, payment_status, payment_amount, notes)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "ssissssds",
        $name,
        $phone,
        $facility_id,
        $start,
        $end,
        $status,
        $payment,
        $amount,
        $notes
    );
}

$stmt->execute();

header("Location: ../admin/bookings.php");
exit;
?>