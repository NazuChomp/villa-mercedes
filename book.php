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
        <div class="facility-card-selector">
            <div class="facility-card">
                <?php if($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="facility-card">
                            <img src="<?= e(url('resources/img/') . $row['image_filename']) ?>" 
                                 alt="<?= e($row['image_filename']) ?>"
                                 width="100px">
                            <div class="card-info">
                                <h3><?= e($row['name']) ?></h3>
                                <p><?= e($row['description']) ?></p>
                                <p><?= e('₱' . $row['rate_amount']) ?></p>
                                <p>click for more details</p>
                            </div>
                        </div>
                    <?php endwhile;?>
                <?php else: ?>
                    <p class="no-data">No Facilities Yet</p>
                <?php endif; ?>
            </div>
            <div class="booking-form">
                <div class="form-row">
                    <label for="fullname">Full Name</label>
                    <input type="text" name="fullname" id="fullname">
                </div>  
            </div>
        </div>
    </main>

</body>
</html>
