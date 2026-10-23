
<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

$name = $_SESSION["full_name"];
$role = $_SESSION["role"];
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager - Paneli</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-page">
    <div class="login-card">
        <div class="logo">✓</div>

        <h1>Përshëndetje!</h1>

        <p class="subtitle">
            Mirë se erdhe,
            <?= htmlspecialchars($name, ENT_QUOTES, "UTF-8") ?>.
        </p>

        <?php if ($role === "admin"): ?>
            <h2>Paneli i administratorit</h2>
            <p class="subtitle">
                Ti je administrator dhe mund ta menaxhosh ekipin.
            </p>
        <?php else: ?>
            <h2>Paneli i punëtorit</h2>
            <p class="subtitle">
                Këtu do të mund të shohësh detyrat e tua.
            </p>
        <?php endif; ?>

        <a class="login-button" href="logout.php">
            Dil nga llogaria →
        </a>
    </div>
</div>
</body>
</html>
