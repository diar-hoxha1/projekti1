
<?php
session_start();
require_once "db.php";

if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["full_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($name == "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Plotëso emrin dhe email-in saktë.";
    }
    elseif (strlen($password) < 8) {
        $error = "Fjalëkalimi duhet të ketë së paku 8 karaktere.";
    }
    else {
        try {
            $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, 'worker')");

            $stmt->execute([
                $name,
                $email,
                password_hash($password, PASSWORD_DEFAULT)
            ]);

            $success = true;

        } catch (PDOException $e) {

            if ($e->getCode() == "23000") {
                $error = "Ky email është regjistruar më parë.";
            }
            else {
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
    <meta name="theme-color" content="#080b16">

    <title>TaskFlow - Krijo llogari</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="style.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: #080b16;
            color: #fff;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .login-page {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 45px 20px;
            position: relative;
            isolation: isolate;
            overflow: hidden;
            background:
                radial-gradient(ellipse at 15% 15%, rgba(111, 66, 255, .18), transparent 40%),
                radial-gradient(ellipse at 85% 85%, rgba(0, 190, 255, .12), transparent 40%),
                #080b16;
        }

        .login-page::before,
        .login-page::after {
            content: "";
            position: absolute;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            filter: blur(100px);
            opacity: .25;
            z-index: -1;
            animation: floatGlow 9s ease-in-out infinite alternate;
        }

        .login-page::before {
            background: #7047ff;
            top: -180px;
            left: -130px;
        }

        .login-page::after {
            background: #008cff;
            bottom: -200px;
            right: -100px;
            animation-delay: -4s;
        }

        .login-card {
            width: 100%;
            max-width: 465px;
            padding: 45px 42px 35px;
            position: relative;
            z-index: 1;
            border: 1px solid rgba(255,255,255,.09);
            border-radius: 28px;
            background: rgba(17, 22, 39, .82);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            box-shadow:
                0 30px 100px rgba(0,0,0,.45),
                inset 0 1px 0 rgba(255,255,255,.06);
            animation: cardEnter .8s cubic-bezier(.2,.8,.2,1) both;
        }

        .login-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 15%;
            right: 15%;
            height: 1px;
            background: linear-gradient(90deg, transparent, #9677ff, #44caff, transparent);
        }

        .logo {
            width: 62px;
            height: 62px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            border-radius: 19px;
            color: white;
            font-size: 29px;
            font-weight: 900;
            background: linear-gradient(135deg, #7955ff, #4d8dff);
            box-shadow: 0 10px 35px rgba(105, 75, 255, .38);
            transform: rotate(-5deg);
            transition: .3s ease;
        }

        .logo:hover {
            transform: rotate(0deg) scale(1.08);
        }

        .login-card h1 {
            text-align: center;
            font-size: 31px;
            font-weight: 800;
            letter-spacing: -1.3px;
            margin: 0 0 10px;
            color: #fff;
        }

        .subtitle {
            text-align: center;
            color: #929bb2;
            font-size: 14px;
            margin: 0 0 32px;
            line-height: 1.7;
        }

        .login-card form {
            display: flex;
            flex-direction: column;
        }

        .login-card label {
            font-size: 12px;
            font-weight: 700;
            color: #c4cbe0;
            margin: 0 0 9px;
            letter-spacing: .3px;
        }

        .login-card input {
            width: 100%;
            height: 53px;
            padding: 0 16px;
            margin-bottom: 21px;
            color: #fff;
            font-family: inherit;
            font-size: 14px;
            background: rgba(255,255,255,.045);
            border: 1px solid rgba(255,255,255,.09);
            border-radius: 12px;
            outline: none;
            transition: .25s ease;
        }

        .login-card input::placeholder {
            color: #626d85;
        }

        .login-card input:hover {
            border-color: rgba(145,115,255,.45);
        }

        .login-card input:focus {
            background: rgba(255,255,255,.07);
            border-color: #8767ff;
            box-shadow: 0 0 0 4px rgba(135,103,255,.12);
        }

        .login-button {
            width: 100%;
            height: 55px;
            margin-top: 5px;
            border: none;
            border-radius: 13px;
            cursor: pointer;
            color: #fff;
            font-family: inherit;
            font-size: 14px;
            font-weight: 800;
            letter-spacing: .2px;
            background: linear-gradient(110deg, #7652ff, #526eff);
            box-shadow: 0 10px 28px rgba(105, 79, 255, .24);
            transition: .3s ease;
        }

        .login-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(105, 79, 255, .4);
            filter: brightness(1.12);
        }

        .login-button:active {
            transform: translateY(0) scale(.98);
        }

        .error,
        .success {
            padding: 14px 16px;
            margin-bottom: 22px;
            border-radius: 12px;
            font-size: 13px;
            line-height: 1.6;
            animation: messageEnter .3s ease;
        }

        .error {
            color: #ff9caa;
            background: rgba(255, 70, 100, .09);
            border: 1px solid rgba(255, 70, 100, .22);
        }

        .success {
            text-align: center;
            color: #7ce9b1;
            background: rgba(40, 210, 130, .09);
            border: 1px solid rgba(40, 210, 130, .2);
        }

        .success a {
            display: inline-block;
            margin-top: 12px;
            color: #fff;
            font-weight: 700;
            text-decoration: none;
            transition: .2s;
        }

        .success a:hover {
            color: #a995ff;
        }

        .signup {
            text-align: center;
            margin: 27px 0 0;
            color: #8993aa;
            font-size: 13px;
        }

        .signup a {
            color: #a995ff;
            font-weight: 700;
            text-decoration: none;
            margin-left: 4px;
            transition: .2s;
        }

        .signup a:hover {
            color: #fff;
        }

        .login-footer {
            text-align: center;
            margin-top: 25px;
            font-size: 10px;
            color: #59647b;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        @keyframes cardEnter {
            from {
                opacity: 0;
                transform: translateY(25px) scale(.97);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes floatGlow {
            from {
                transform: translate(0, 0);
            }
            to {
                transform: translate(45px, 35px);
            }
        }

        @keyframes messageEnter {
            from {
                opacity: 0;
                transform: translateY(-7px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 520px) {
            .login-page {
                padding: 25px 15px;
            }

            .login-card {
                padding: 36px 24px 28px;
                border-radius: 23px;
            }

            .login-card h1 {
                font-size: 27px;
            }

            .logo {
                width: 56px;
                height: 56px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: .01ms !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body>

<div class="login-page">

    <div class="login-card">

        <div class="logo">✓</div>

        <h1>Krijo llogari</h1>
        <p class="subtitle">
            Bashkohu me ekipin dhe menaxho detyrat në TaskFlow.
        </p>

        <?php if ($error != ""): ?>
            <div class="error">
                <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>

            <div class="success">
                Llogaria u krijua me sukses!
                <br>
                <a href="index.php">Kyçu në llogarinë tënde →</a>
            </div>

        <?php else: ?>

            <form method="POST">

                <label for="full_name">EMRI DHE MBIEMRI</label>
                <input
                    id="full_name"
                    name="full_name"
                    type="text"
                    maxlength="100"
                    placeholder="Shkruaj emrin tënd"
                    autocomplete="name"
                    required
                >

                <label for="email">EMAIL ADRESA</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    maxlength="150"
                    placeholder="emri@example.com"
                    autocomplete="email"
                    required
                >

                <label for="password">FJALËKALIMI</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    minlength="8"
                    placeholder="Minimum 8 karaktere"
                    autocomplete="new-password"
                    required
                >

                <button class="login-button" type="submit">
                    Krijo llogarinë →
                </button>

            </form>

        <?php endif; ?>

        <p class="signup">
            Ke llogari?
            <a href="index.php">Kyçu këtu</a>
        </p>

        <div class="login-footer">
            TASKFLOW · WORK SMARTER
        </div>

    </div>

</div>

</body>
</html>
