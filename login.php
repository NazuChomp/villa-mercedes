<?php
session_start();
require_once 'utils/helper.php';
require_once 'database/database.php';
$currentPage = basename($_SERVER['PHP_SELF']);

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $conn = connection();

    $username = trim($_POST['username']) ?? '';
    $password = $_POST['password'] ?? '';

    if($username === '' || $password === '') {
        flash('err', 'Required: Username and password');
        redirect('login.php');
    }

    $stmt = $conn->prepare("SELECT id, username, password_hash, full_name FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if(password_verify($password, $row['password_hash'])) {
            session_regenerate_id(true);

            $_SESSION['user'] = [
                'id' => (int)$row['id'],
                'username' => $row['username'],
                'password' => $row['password'],
                'full_name' => $row['full_name']
            ];

            redirect('admin/dashboard.php');
            flash('ok', 'Login Successfully');
        } else {
            flash('err', 'Invalid Credentials');
            redirect('login.php');
        }
    } else {
        flash('err', 'Invalid Credentials');
        redirect('login.php');
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="./resources/css/header-footer.css">
    <link rel="stylesheet" href="./resources/css/login.css">
</head>
<body>

    <header>
        <div class="header-container">
            <a href="<?= e(url('index.php')) ?>" class="brand-label">Villa Mercedes</a>
            <div class="header-nav">
                <a href="<?= e(url('index.php')) ?>" class="<?= $currentPage === 'index.php' ? 'is-active' : '' ?>">Home</a>
                <a href="<?= e(url('book.php')) ?>" class="<?= $currentPage === 'book.php' ? 'is-active' : '' ?>">Boook</a>
                <a href="<?= e(url('login.php')) ?>" class="<?= $currentPage === 'login.php' ? 'is-active' : '' ?>">
                     <?= isset($_SESSION['user']) ? 'Dashboard Management' : 'Login' ?> 
                </a>
            </div>
        </div>
    </header>

    <main class="main-content-login">
        <div class="login-page">
            <div class="login-card">
                <h1>Administrator Login</h1>
                <p class="sub">Villa Mercedes system</p>
                
                <?php
                if (!empty($_SESSION['flash'])) {
                    $type = $_SESSION['flash']['type'];
                    $text = $_SESSION['flash']['text'];
                    
                    echo "<div class='msg " . htmlspecialchars($type) . "'>" . htmlspecialchars($text) . "</div>";
                    
                    unset($_SESSION['flash']);
                    }
                ?>

                <form action="<?= e(url('login.php')) ?>" method="post">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>

                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>  
                    <div class="form-action">
                        <button type="submit" class="btn btn-login">Login</button>
                        <a class="btn btn-back" href="<?= e(url('index.php')) ?>">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
