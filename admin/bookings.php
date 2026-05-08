
<?php
session_start();
require_once '../utils/helper.php';
require_once '../database/database.php';
$currentPage = basename($_SERVER['PHP_SELF']);

$editId = isset($_GET['edit']) ? (int)$_GET['edit'] : null;
$editRow = null;

$conn = connection();
if ($editId) {
    $editStmt = $conn->prepare("SELECT * FROM bookings WHERE id = ?");
    $editStmt->bind_param("i", $editId);
    $editStmt->execute();
    $editRow = $editStmt->get_result()->fetch_assoc();
}
$stmt = $conn->prepare("SELECT * FROM facilities");
$stmt->execute();
$result = $stmt->get_result();

$table_data = $conn->prepare("
    SELECT b.*, f.name AS facility_name 
    FROM bookings b
    LEFT JOIN facilities f ON b.facility_id = f.id
");
$table_data->execute();
$table_data_result = $table_data->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking-admin|Villa Mercedes</title>
    <link rel="stylesheet" href="../resources/css/header-footer.css">
    <link rel="stylesheet" href="../resources/css/admin-nav.css">
    <link rel="stylesheet" href="../resources/css/admin-booking.css">
</head>
<body>

<header>
    <div class="header-container">
        <a href="<?= e(url('index.php')) ?>" class="brand-label">Villa Mercedes</a>
        <div class="header-nav">
            <a href="<?= e(url('index.php')) ?>" class="<?= $currentPage === 'index.php' ? 'is-active' : '' ?>">Home</a>
            <a href="<?= e(url('book.php')) ?>" class="<?= $currentPage === 'book.php' ? 'is-active' : '' ?>">Boook</a>
            <a href="<?= e(url('login.php')) ?>" class="<?= e('is-active') ?>">
                <?= isset($_SESSION['user']) ? 'Dashboard Management' : 'Login' ?>
            </a>
        </div>
    </div>
</header>

<nav class="admin-subnav">
    <div class="admin-subnav-inner">
        <a href="<?= e(url('admin/dashboard.php')) ?>" class="<?= $currentPage === 'dashboard.php' ? 'is-active' : '' ?>">Dashboard</a>
        <a href="<?= e(url('admin/facilities.php')) ?>" class="<?= $currentPage === 'facilities.php' ? 'is-active' : '' ?>">Facilities</a>
        <a href="<?= e(url('admin/bookings.php')) ?>" class="<?= $currentPage === 'bookings.php' ? 'is-active' : '' ?>">Bookings</a>
        <a href="<?= e(url('admin/reports.php')) ?>" class="<?= $currentPage === 'reports.php' ? 'is-active' : '' ?>">Reports</a>
        <a href="<?= e(url('index.php')) ?>" target="_blank" >View site</a>
    </div>
</nav>

<main class="admin-booking">
    <div class="page-head">
        <div>
            <h2>Bookings</h2>
            <p>Search, filter, and maintain reservations.</p>
        </div>
        <?php if ($editRow): ?>
            <a class="cancel-btn btn" href="<?= e(url('admin/bookings.php')) ?>">Cancel edit</a>
        <?php endif; ?>
    </div>

    <div class="filters">
        <form action="" method="get">
            <div class="form-row">
                <label for="filter_facility">Facility</label>
                <select name="filter_facility" id="filter_facility">
                    <option value="">All</option>
                    <?php
                    while($row = $result->fetch_assoc()):
                    ?>
                    <option value="<?= e($row['id']) ?>"><?= e($row['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-row">
                <label for="filter_status">Status</label>
                <select name="filter_status" id="filter_status">
                    <option value="">All</option>
                    <option value="Pending">Pending</option>
                    <option value="Confirmed">Confirmed</option>
                    <option value="Cancel">Cancel</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>
            <div class="form-row">
                <label for="from_date">From</label>
                <input type="date" id="from_date" name="from_date">
            </div>
            <div class="form-row">
                <label for="to_date">To</label>
                <input type="date" name="to_date" id="to_date">
            </div>
            <div class="form-row">
                <label for="search_input">Search</label>
                <input type="text" name="search_input" id="search_input">
            </div>
            <button class="apply-btn btn">Apply</button>
        </form>
    </div>

    <div class="form-result-wrapper">
        <div class="booking-form">
            <h2><?= !$editRow ? 'New Booking' : 'Edit Booking' ?></h2>
            <form action="../repositories/bookingRepository.php" method="POST">
                <?php if ($editRow): ?>
                    <input type="hidden" name="booking_id" value="<?= $editRow['id'] ?>">
                <?php endif; ?>

                <div class="form-row">
                    <label for="name">Guest Name</label>
                    <input type="text" name="name" id="name" value="<?= $editRow ? e($editRow['guest_name']) : '' ?>">
                </div>

                <div class="form-row">
                    <label for="phone_number">Phone Number</label>
                    <input type="text" name="phone_number" id="phone_number" value="<?= $editRow ? e($editRow['phone_number']) : '' ?>">
                </div>

                <div class="form-row">
                    <label for="facility">Facility</label>
                    <select name="facility" id="facility">
                        <option value="">All</option>
                        <?php $result->data_seek(0); while($row = $result->fetch_assoc()): ?>
                            <option value="<?= e($row['id']) ?>" 
                                <?= ($editRow && $editRow['facility_id'] == $row['id']) ? 'selected' : '' ?>>
                                <?= e($row['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-row-2">
                    <div>
                        <label for="start_date">Start</label>
                        <input type="date" name="start_date" id="start_date" value="<?= $editRow ? e($editRow['date_start']) : '' ?>">
                    </div>
                    <div>
                        <label for="end_date">End</label>
                        <input type="date" name="end_date" id="end_date" value="<?= $editRow ? e($editRow['date_end']) : '' ?>">
                    </div>
                </div>

                <div class="form-row-2">
                    <div>
                        <label for="status">Status</label>
                        <select name="status" id="status">
                            <?php foreach(['Pending','Confirmed','Cancel','Completed'] as $s): ?>
                                <option value="<?= $s ?>" <?= ($editRow && $editRow['status'] === $s) ? 'selected' : '' ?>>
                                    <?= $s ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="payment">Payment</label>
                        <select name="payment" id="payment">
                            <?php foreach(['Unpaid','Partial','Paid'] as $p): ?>
                                <option value="<?= $p ?>" <?= ($editRow && $editRow['payment_status'] === $p) ? 'selected' : '' ?>>
                                    <?= $p ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <label for="amount">Amount</label>
                    <input type="number" name="amount" id="amount" value="<?= $editRow ? e($editRow['payment_amount']) : '' ?>">
                </div>

                <div class="form-row">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes"><?= $editRow ? e($editRow['notes']) : '' ?></textarea>
                </div>

                <button type="submit" class="btn submit"><?= !$editRow ? 'Create Booking' : 'Update Booking' ?></button>
            </form>
        </div>

        <div class="result-container">
            <h2>Results</h2>
            <table>
                <thead>
                    <tr>
                        <th>Guest</th>
                        <th>facility</th>
                        <th>Dates</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($table_data_result->num_rows > 0):  ?>
                        <?php while($table_row = $table_data_result->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <?= e($table_row['guest_name']) ?>
                                    <span class="number"><?= e($table_row['phone_number']) ?></span>
                                </td>
                                <td><?= e($table_row['facility_name']) ?></td>
                                <td><?= e($table_row['date_start'] . ' → ' . $table_row['date_end']) ?></td>
                                <td>
                                    <span class="status-badge status-<?= strtolower(e($table_row['status'])) ?>">
                                        <?= strtoupper(e($table_row['status'])) ?>
                                    </span>
                                </td>
                                <td><?= e('₱' . $table_row['payment_amount'] . ' · ' . $table_row['payment_status']) ?></td>
                                <td>
                                    <div class="action-cell">
                                        <a href="<?= e(url('admin/bookings.php?edit=' . (int) $table_row['id'])) ?>" class="edit-btn">Edit</a>
                                        <form action="" method="POST" onsubmit="return confirm('Delete this booking?')">
                                            <input type="hidden" name="booking_id" value="<?= (int) $table_row['id'] ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <button type="submit" class="delete-btn">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="no-data">No Bookings Yet</p>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

</body>
</html>
