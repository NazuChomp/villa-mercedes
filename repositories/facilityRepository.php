<?php
require_once '../database/database.php';

$conn = connection();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../admin/facilities.php");
    exit;
}


if (isset($_POST['action']) && $_POST['action'] === 'delete') {

    $id = (int)$_POST['facility_id'];

    $stmt = $conn->prepare("DELETE FROM facilities WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: ../admin/facilities.php");
    exit;
}


$id = $_POST['facility_id'] ?? null;

$name = trim($_POST['name']);
$description = trim($_POST['description']);
$capacity = (int)$_POST['capacity'];
$rate = (float)$_POST['rate'];
$rate_unit = $_POST['rate_unit'];


$imageName = null;

$hasImage = !empty($_FILES['facility_photo']['name']);

if ($hasImage) {
    $imageName = time() . "_" . basename($_FILES['facility_photo']['name']);

    move_uploaded_file(
        $_FILES['facility_photo']['tmp_name'],
        "../resources/img/" . $imageName
    );
}


if ($id) {

    if ($hasImage) {

        $stmt = $conn->prepare("
            UPDATE facilities
            SET name=?, description=?, image_filename=?, capacity=?, rate_amount=?, rate_unit=?
            WHERE id=?
        ");

        $stmt->bind_param(
            "sssidsi",
            $name,
            $description,
            $imageName,
            $capacity,
            $rate,
            $rate_unit,
            $id
        );

    } else {

        $stmt = $conn->prepare("
            UPDATE facilities
            SET name=?, description=?, capacity=?, rate_amount=?, rate_unit=?
            WHERE id=?
        ");

        $stmt->bind_param(
            "ssidsi",
            $name,
            $description,
            $capacity,
            $rate,
            $rate_unit,
            $id
        );
    }

    $stmt->execute();


} else {

    $stmt = $conn->prepare("
        INSERT INTO facilities
        (name, description, image_filename, capacity, rate_amount, rate_unit)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "sssids",
        $name,
        $description,
        $imageName,
        $capacity,
        $rate,
        $rate_unit
    );

    $stmt->execute();
}

header("Location: ../admin/facilities.php");
exit;