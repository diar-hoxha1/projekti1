
<?php
session_start(); // Fillon sesionin për të ruajtur të dhënat e përdoruesit

require_once "db.php"; // Përfshin lidhjen me databazën

// Kontrollon nëse përdoruesi është i kyçur
if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php"); // Ridrejton përdoruesin te dashboard
    exit; // Ndërpret ekzekutimin e kodit
}

// Krijon një variabël për ruajtjen e mesazheve të gabimit
$error = "";

// Krijon një variabël boolean për të kontrolluar regjistrimin me sukses
$success = false;

// Kontrollon nëse forma është dërguar me metodën POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Merr emrin e plotë nga forma dhe largon hapësirat e panevojshme
    $name = trim($_POST["full_name"] ?? "");

    // Merr email-in nga forma dhe largon hapësirat e panevojshme
    $email = trim($_POST["email"] ?? "");

    // Merr fjalëkalimin e shkruar nga përdoruesi
    $password = $_POST["password"] ?? "";

    // Kontrollon nëse emri është bosh ose email-i nuk është në format të vlefshëm
    if ($name == "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {

        // Shfaq mesazhin e gabimit nëse të dhënat nuk janë të sakta
        $error = "Plotëso emrin dhe email-in saktë.";

    }

    // Kontrollon nëse fjalëkalimi ka më pak se 8 karaktere
    elseif (strlen($password) < 8) {

        // Shfaq mesazhin nëse fjalëkalimi është më i shkurtër se 8 karaktere
        $error = "Fjalëkalimi duhet të ketë së paku 8 karaktere.";

    }

    // Nëse të dhënat janë të sakta, vazhdon procesi i regjistrimit
    else {

        // Fillon blloku try për të kapur gabimet gjatë punës me databazën
        try {

            // Përgatit komandën SQL për të shtuar një përdorues të ri në tabelën users
            // Roli i përdoruesit caktohet automatikisht si worker
            $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, 'worker')");

            // Ekzekuton komandën SQL dhe dërgon të dhënat në databazë
            $stmt->execute([

                // Dërgon emrin e plotë të përdoruesit
                $name,

                // Dërgon email-in e përdoruesit
                $email,

                // Enkripton (hash-on) fjalëkalimin përpara ruajtjes në databazë
                password_hash($password, PASSWORD_DEFAULT)

            ]);

            // Ndryshon vlerën në true për të treguar se regjistrimi ishte i suksesshëm
            $success = true;

        // Kap gabimet që mund të ndodhin gjatë komunikimit me databazën
        } catch (PDOException $e) {

            // Kontrollon nëse gabimi është për shkak të një vlere që ekziston tashmë
            if ($e->getCode() == "23000") {

                // Shfaq mesazhin nëse email-i është regjistruar më parë
                $error = "Ky email është regjistruar më parë.";

            }

            // Nëse gabimi është i një lloji tjetër
            else {

                // Regjistron detajet e gabimit në log për administratorin
                error_log($e->getMessage());

                // Shfaq një mesazh të përgjithshëm për përdoruesin
                $error = "Ndodhi një gabim gjatë regjistrimit.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<!-- Tregon se dokumenti përdor HTML5 -->
<html lang="sq">
<!-- Fillon dokumenti HTML dhe cakton gjuhën shqipe -->

<head>
    <!-- Përcakton kodimin e karaktereve UTF-8 -->
    <meta charset="UTF-8">

    <!-- Bën që faqja të përshtatet me madhësinë e ekranit -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Përcakton titullin që shfaqet në skedën e shfletuesit -->
    <title>Task Manager - Regjistrimi</title>

    <!-- Lidh dokumentin HTML me fajllin CSS për dizajnin -->
    <link rel="stylesheet" href="style.css">
</head>

<!-- Fillon pjesa e dukshme e faqes -->
<body>

<!-- Krijon kontejnerin kryesor të faqes së regjistrimit -->
<div class="login-page">

    <!-- Krijon kartelën ku vendoset formulari i regjistrimit -->
    <div class="login-card">

        <!-- Shfaq logon me simbolin e konfirmimit -->
        <div class="logo">✓</div>

        <!-- Shfaq titullin kryesor të faqes -->
        <h1>Krijo llogari</h1>

        <!-- Shfaq një përshkrim të shkurtër për regjistrimin -->
        <p class="subtitle">Regjistrohu në Task Manager.</p>

        <!-- Kontrollon nëse ekziston ndonjë mesazh gabimi -->
        <?php if ($error != ""): ?>

            <!-- Shfaq kutinë e mesazhit të gabimit -->
            <div class="error">

                <!-- Shfaq mesazhin duke mbrojtur faqen nga HTML i padëshiruar -->
                <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>

            </div>

        <!-- Përfundon kontrollin e mesazhit të gabimit -->
        <?php endif; ?>

        <!-- Kontrollon nëse regjistrimi është kryer me sukses -->
        <?php if ($success): ?>

            <!-- Shfaq kutinë e mesazhit të suksesit -->
            <div class="success">

                <!-- Shfaq mesazhin se llogaria është krijuar -->
                Llogaria u krijua me sukses!

                <!-- Krijon lidhjen për t'u kthyer te faqja e kyçjes -->
                <a href="index.php">Kyçu këtu</a>

            <!-- Përfundon kutinë e suksesit -->
            </div>

        <!-- Nëse regjistrimi nuk është kryer, shfaq formularin -->
        <?php else: ?>

            <!-- Fillon formulari që dërgon të dhënat përmes metodës POST -->
            <form method="POST">

                <!-- Etiketa për fushën e emrit dhe mbiemrit -->
                <label for="full_name">Emri dhe mbiemri</label>

                <!-- Fusha ku përdoruesi shkruan emrin e plotë -->
                <!-- maxlength kufizon emrin në 100 karaktere -->
                <!-- required e bën këtë fushë të detyrueshme -->
                <input id="full_name" name="full_name" maxlength="100" required>

                <!-- Etiketa për fushën e email-it -->
                <label for="email">Email adresa</label>

                <!-- Fusha për shkrimin e email-it -->
                <!-- type="email" kontrollon formatin e email-it -->
                <!-- maxlength kufizon email-in në 150 karaktere -->
                <!-- required e bën fushën të detyrueshme -->
                <input id="email" type="email" name="email" maxlength="150" required>

                <!-- Etiketa për fushën e fjalëkalimit -->
                <label for="password">Fjalëkalimi</label>

                <!-- Fusha për shkrimin e fjalëkalimit -->
                <!-- type="password" fsheh karakteret e shkruara -->
                <!-- minlength kërkon së paku 8 karaktere -->
                <!-- required e bën fushën të detyrueshme -->
                <input id="password" type="password" name="password" minlength="8" required>

                <!-- Butoni që dërgon formularin për regjistrim -->
                <button class="login-button" type="submit">

                    <!-- Teksti që shfaqet në butonin e regjistrimit -->
                    Regjistrohu →

                <!-- Përfundon butoni -->
                </button>

            <!-- Përfundon formulari -->
            </form>

        <!-- Përfundon kushti if/else -->
        <?php endif; ?>

        <!-- Shfaq tekstin për përdoruesit që kanë llogari -->
        <p class="signup">

            <!-- Pyet nëse përdoruesi ka llogari ekzistuese -->
            Ke llogari?

            <!-- Krijon lidhjen për t'u kyçur në sistem -->
            <a href="index.php">Kyçu</a>

        <!-- Përfundon paragrafin -->
        </p>

    <!-- Përfundon kartela e regjistrimit -->
    </div>

<!-- Përfundon kontejneri kryesor -->
</div>

<!-- Përfundon pjesa e dukshme e dokumentit -->
</body>

<!-- Përfundon dokumenti HTML -->
</html>
