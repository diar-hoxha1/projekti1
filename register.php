
<?php
session_start();
require_once "db.php";

if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["full_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($name === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Plotëso emrin dhe email-in saktë.";
    } elseif (strlen($password) < 8) {
        $error = "Fjalëkalimi duhet të ketë së paku 8 karaktere.";
    } else {
        try {
            $stmt = $pdo->prepare(
                "INSERT INTO users (full_name, email, password, role)
                 VALUES (?, ?, ?, 'worker')"
            );

            $stmt->execute([
                $name,
                $email,
                password_hash($password, PASSWORD_DEFAULT)
            ]);

            $success = true;
        } catch (PDOException $e) {
            if ($e->getCode() === "23000") {
                $error = "Ky email është regjistruar më parë.";
            } else {
                error_log($e->getMessage());
                $error = "Ndodhi një gabim gjatë regjistrimit.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager - Regjistrimi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-page">
    <div class="login-card">
        <div class="logo">✓</div>
        <h1>Krijo llogari</h1>
        <p class="subtitle">Regjistrohu në Task Manager.</p>

        <?php if ($error !== ""): ?>
            <div class="error">
                <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success">
                Llogaria u krijua me sukses!
                <a href="index.php">Kyçu këtu</a>
            </div>
        <?php else: ?>
            <form method="POST">
                <label for="full_name">Emri dhe mbiemri</label>
                <input id="full_name" name="full_name"
                       maxlength="100" required>

                <label for="email">Email adresa</label>
                <input id="email" type="email" name="email"
                       maxlength="150" required>

                <label for="password">Fjalëkalimi</label>
                <input id="password" type="password" name="password"
                       minlength="8" required>

                <button class="login-button" type="submit">
                    Regjistrohu →
                </button>
            </form>
        <?php endif; ?>

        <p class="signup">
            Ke llogari? <a href="index.php">Kyçu</a>
        </p>
    </div>
</div>
</body>
</html>
