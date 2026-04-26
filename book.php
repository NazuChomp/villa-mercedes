<?php
session_start();
require_once 'utils/helper.php';
require_once 'database/database.php';

$currentPage = basename($_SERVER['PHP_SELF']);

$conn = connection();
$stmt = $conn->prepare("SELECT * FROM facilities");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="./resources/css/header-footer.css">
    <link rel="stylesheet" href="./resources/css/booking-page.css">

</head>
<body>
<header>
    <div class="header-container">
        <a href="<?= e(url('index.php')) ?>" class="brand-label">Villa Mercedes</a>
        <div class="header-nav">
            <a href="<?= e(url('index.php')) ?>" class="<?= $currentPage === 'index.php' ? 'is-active' : '' ?>">Home</a>
            <a href="<?= e(url('book.php')) ?>" class="<?= $currentPage === 'book.php' ? 'is-active' : '' ?>">Boook</a>
            <a href="<?= e(url('login.php')) ?>" class="<?= $currentPage === 'dashboard.php' ? 'is-active' : '' ?>">
                    <?= isset($_SESSION['user']) ? 'Dashboard Management' : 'Login' ?> 
            </a>
        </div>
    </div>
</header>   
    
<main class="main-booking">

    <div class="header-block">
        <h2>Reserve your stay</h2>
        <p>select your preferred accommodation and fill out the details below</p>
    </div>

    <div class="facility-form-wrapper">
        <div class="facility-card-selector">
            <?php if($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    
                    <div class="facility-card" data-value="<?= e($row['name']) ?>">
                        <img src="<?= e(url('resources/img/') . $row['image_filename']) ?>" 
                            alt="<?= e($row['image_filename']) ?>"
                            width="100px">
                        <div class="card-info">
                            <h3><?= e($row['name']) ?></h3>
                            <p><?= e($row['description']) ?></p>
                            <p class="rate"><?= e('₱' . $row['rate_amount']) ?></p>
                            <p class="more-detail">click for more details</p>
                        </div>
                    </div>

                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-data">No Facilities Yet</p>
            <?php endif; ?>

        </div>

        <form action="" class="booking-form">
            <div class="form-row">
                <label for="fullname">Full Name</label>
                <input type="text" name="fullname" id="fullname" placeholder="e.g. Juan Dela Cruz" required>
            </div>  
            <div class="form-row">
                <label for="number">Number</label>
                <input type="text" name="number" id="number" placeholder="0912 345 6789" required>
            </div>  
            <div class="form-row">
                <label for="check-in-date">Check-in Date</label>
                <input type="date" name="check-in-date" id="check-in-date" required>
            </div>  
            <div class="form-row">
                <label for="check-out-date">Check-out Date</label>
                <input type="date" name="check-out-date" id="check-out-date" required>
            </div>
            <div class="form-row">
                <label for="facility">Select accommodation</label>
                <select name="facility" id="facility">
                    <option value="">Choose Accommodation</option>
                    <?php
                    $result->data_seek(0);
                    while($row = $result->fetch_assoc()):
                    ?>
                    <option value="<?= e($row['name']) ?>"><?= e($row['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>  
            <div class="form-row">
                <label for="request">Special Request</label>
                <textarea name="request" id="request"></textarea>
            </div>
            <button type="submit" class="form-row form-btn">Send Resevation Request</button>  
        </form>
    </div>

</main>
    <script src="utils/scripts.js"></script>
</body>
</html>
