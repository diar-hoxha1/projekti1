<?php
session_start();
require_once "db.php";

if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Ju lutem shkruani një email të vlefshëm.";
    } else {
        $stmt = $pdo->prepare(
            "SELECT id, full_name, email, password, role
             FROM users
             WHERE email = ?
             LIMIT 1"
        );
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user["password"])) {
            session_regenerate_id(true);

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["full_name"] = $user["full_name"];
            $_SESSION["role"] = $user["role"];

            header("Location: dashboard.php");
            exit;
        }

        $error = "Email ose fjalëkalim i pasaktë.";
    }
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TaskFlow | Kyçu</title>

    <link rel="stylesheet" href="style.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background: #080b16;
            color: #f8fafc;
        }

        .page {
            min-height: 100vh;
            background:
                radial-gradient(ellipse at 15% 15%, rgba(124, 58, 237, .19), transparent 40%),
                radial-gradient(ellipse at 85% 85%, rgba(59, 130, 246, .13), transparent 40%),
                #080b16;
            position: relative;
            overflow: hidden;
        }

        .navbar {
            max-width: 1250px;
            margin: auto;
            padding: 27px 25px;
            position: relative;
            z-index: 2;
        }

        .brand {
            font-size: 23px;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .brand-logo {
            background: linear-gradient(135deg, #a78bfa, #6366f1);
            box-shadow: 0 5px 25px rgba(124, 58, 237, .4);
            border-radius: 12px;
        }

        .brand-highlight {
            color: #a78bfa;
        }

        .nav-link {
            color: #a78bfa;
            transition: .25s;
        }

        .nav-link:hover {
            color: white;
        }

        .main-content {
            max-width: 1190px;
            margin: auto;
            padding: 65px 25px 85px;
            gap: 90px;
            align-items: center;
        }

        .welcome-badge {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 10px 15px;
            border: 1px solid rgba(167, 139, 250, .25);
            border-radius: 50px;
            background: rgba(139, 92, 246, .09);
            color: #c4b5fd;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.3px;
        }

        .status-dot {
            width: 7px;
            height: 7px;
            background: #a78bfa;
            border-radius: 50%;
            box-shadow: 0 0 12px #a78bfa;
        }

        .welcome-section h1 {
            font-size: clamp(42px, 5vw, 65px);
            line-height: 1.08;
            letter-spacing: -3px;
            font-weight: 850;
            margin: 28px 0 20px;
            color: #fff;
        }

        .welcome-section h1 span {
            background: linear-gradient(100deg, #c4b5fd, #818cf8, #60a5fa);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .welcome-description {
            color: #929bb0;
            line-height: 1.8;
            font-size: 15px;
            max-width: 440px;
        }

        .feature-list {
            margin-top: 35px;
        }

        .feature {
            margin-bottom: 22px;
            align-items: center;
        }

        .feature-icon {
            width: 39px;
            height: 39px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(139, 92, 246, .13);
            border: 1px solid rgba(167, 139, 250, .15);
        }

        .feature strong {
            color: #f1f5f9;
            font-size: 14px;
        }

        .feature p {
            color: #8791a6;
            font-size: 12px;
            margin-top: 5px;
        }

        .mini-dashboard {
            margin-top: 38px;
            max-width: 430px;
            padding: 23px;
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,.09);
            background: linear-gradient(135deg, rgba(30, 34, 58, .8), rgba(15, 20, 38, .8));
            box-shadow: 0 20px 50px rgba(0,0,0,.2);
        }

        .mini-label {
            color: #a78bfa;
            font-size: 9px;
            letter-spacing: 2px;
            font-weight: 800;
        }

        .mini-top h3 {
            margin-top: 8px;
            color: #fff;
            font-size: 17px;
        }

        .mini-symbol {
            color: #a78bfa;
            font-size: 22px;
        }

        .progress-info {
            margin-top: 23px;
            color: #a1aabe;
            font-size: 12px;
        }

        .progress-tag {
            color: #c4b5fd;
        }

        .progress-bar {
            height: 6px;
            background: #252b40;
            border-radius: 20px;
            overflow: hidden;
            margin-top: 12px;
        }

        .progress-fill {
            width: 72%;
            height: 100%;
            border-radius: 20px;
            background: linear-gradient(90deg, #8b5cf6, #60a5fa);
            box-shadow: 0 0 15px rgba(139,92,246,.5);
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            padding: 42px 37px;
            border-radius: 26px;
            background: rgba(17, 22, 40, .86);
            border: 1px solid rgba(255,255,255,.10);
            box-shadow:
                0 30px 90px rgba(0,0,0,.45),
                0 0 45px rgba(124,58,237,.08);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            position: relative;
        }

        .card-icon {
            width: 52px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            background: linear-gradient(135deg, #8b5cf6, #6366f1);
            color: white;
            font-size: 25px;
            box-shadow: 0 8px 25px rgba(124,58,237,.35);
        }

        .card-eyebrow {
            margin-top: 27px;
            color: #a78bfa;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 2px;
        }

        .login-card h2 {
            color: #fff;
            font-size: 27px;
            letter-spacing: -.8px;
            margin-top: 9px;
        }

        .card-description {
            color: #8993a8;
            font-size: 13px;
            margin-top: 9px;
            margin-bottom: 28px;
        }

        .error-message {
            padding: 13px;
            border-radius: 11px;
            background: rgba(239, 68, 68, .10);
            border: 1px solid rgba(239, 68, 68, .25);
            color: #fca5a5;
            font-size: 12px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 21px;
        }

        .form-group label {
            display: block;
            color: #dce2ef;
            font-size: 12px;
            font-weight: 650;
            margin-bottom: 10px;
        }

        .secure-label {
            color: #a78bfa;
            font-size: 10px;
        }

        .input-wrap {
            min-height: 53px;
            display: flex;
            align-items: center;
            border: 1px solid #30374d;
            background: #0c1121;
            border-radius: 12px;
            transition: .25s;
            padding: 0 14px;
        }

        .input-wrap:focus-within {
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139,92,246,.13);
            background: #10162a;
        }

        .input-icon {
            color: #a78bfa;
            font-size: 17px;
            margin-right: 12px;
        }

        .input-wrap input {
            width: 100%;
            background: transparent !important;
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
            color: #fff !important;
            font-size: 13px;
            min-width: 0;
        }

        .input-wrap input::placeholder {
            color: #606b81;
        }

        .show-password {
            background: transparent;
            border: none;
            color: #a78bfa;
            cursor: pointer;
            font-size: 11px;
            font-weight: 700;
            padding: 8px;
        }

        .show-password:hover {
            color: white;
        }

        .submit-button {
            width: 100%;
            min-height: 54px;
            margin-top: 9px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(110deg, #8b5cf6, #6366f1);
            color: white;
            font-size: 13px;
            font-weight: 750;
            cursor: pointer;
            box-shadow: 0 8px 25px rgba(99,102,241,.25);
            transition: .3s;
        }

        .submit-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(124,58,237,.4);
            filter: brightness(1.1);
        }

        .button-arrow {
            margin-left: 10px;
            font-size: 17px;
        }

        .divider {
            margin: 28px 0;
        }

        .divider p {
            color: #69758d;
            font-size: 9px;
            letter-spacing: 1.7px;
        }

        .divider span {
            background: #293149;
        }

        .access-info {
            padding: 15px;
            background: rgba(139,92,246,.07);
            border: 1px solid rgba(139,92,246,.13);
            border-radius: 13px;
        }

        .access-icon {
            color: #a78bfa;
            font-size: 22px;
        }

        .access-info strong {
            color: #e7eaff;
            font-size: 12px;
        }

        .access-info p {
            color: #8791a6;
            font-size: 10px;
            margin-top: 5px;
        }

        .register-footer {
            text-align: center;
            color: #8993a8;
            font-size: 11px;
            margin-top: 27px;
        }

        .register-footer a {
            color: #a78bfa;
            font-weight: 750;
            text-decoration: none;
        }

        .register-footer a:hover {
            color: white;
        }

        .footer {
            color: #69758d;
            font-size: 11px;
            max-width: 1190px;
            margin: auto;
            padding: 20px 25px;
            border-top: 1px solid rgba(255,255,255,.06);
        }

        .footer i {
            background: #8b5cf6;
        }

        @media (max-width: 850px) {
            .main-content {
                padding-top: 35px;
                gap: 55px;
            }

            .welcome-section {
                text-align: center;
            }

            .welcome-description {
                margin: auto;
            }

            .feature {
                text-align: left;
            }

            .mini-dashboard {
                margin-left: auto;
                margin-right: auto;
                text-align: left;
            }

            .login-card {
                margin: auto;
            }
        }

        @media (max-width: 500px) {
            .navbar {
                padding: 20px 17px;
            }

            .nav-text {
                display: none;
            }

            .main-content {
                padding: 35px 17px 55px;
                gap: 45px;
            }

            .welcome-section h1 {
                font-size: 43px;
                letter-spacing: -2px;
            }

            .welcome-badge {
                font-size: 8px;
            }

            .login-card {
                padding: 30px 22px;
                border-radius: 20px;
            }

            .footer {
                padding: 18px;
                flex-wrap: wrap;
                gap: 10px;
            }
        }
    </style>
</head>

<body>

<div class="page">

    <header class="navbar">
        <a href="index.php" class="brand">
            <span class="brand-logo">T</span>
            <span>task<span class="brand-highlight">flow</span></span>
        </a>

        <div class="nav-right">
            <span class="nav-text">Nuk ke llogari?</span>
            <a href="register.php" class="nav-link">Regjistrohu ↗</a>
        </div>
    </header>

    <main class="main-content">

        <section class="welcome-section">
            <div class="welcome-badge">
                <span class="status-dot"></span>
                PLATFORMË PËR MENAXHIMIN E PUNËS
            </div>

            <h1>
                Puna jote,<br>
                <span>më e organizuar.</span>
            </h1>

            <p class="welcome-description">
                Menaxho detyrat, organizo ekipin dhe ndiq
                progresin tënd në një vend të vetëm.
            </p>

            <div class="feature-list">

                <div class="feature">
                    <span class="feature-icon purple">✓</span>
                    <div>
                        <strong>Organizim i thjeshtë</strong>
                        <p>Gjithçka që të duhet, në një panel.</p>
                    </div>
                </div>

                <div class="feature">
                    <span class="feature-icon blue">↗</span>
                    <div>
                        <strong>Progres i dukshëm</strong>
                        <p>Mbaje ekipin të fokusuar te rezultatet.</p>
                    </div>
                </div>

                <div class="feature">
                    <span class="feature-icon green">♧</span>
                    <div>
                        <strong>Punë në ekip</strong>
                        <p>Një hapësirë për administratorë dhe punëtorë.</p>
                    </div>
                </div>

            </div>

            <div class="mini-dashboard">
                <div class="mini-top">
                    <div>
                        <span class="mini-label">WORKSPACE</span>
                        <h3>Progresi i ekipit</h3>
                    </div>
                    <span class="mini-symbol">↗</span>
                </div>

                <div class="progress-info">
                    <span>Organizimi fillon këtu</span>
                    <span class="progress-tag">TaskFlow</span>
                </div>

                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>
            </div>
        </section>

        <section class="login-card">

            <div class="card-icon">↗</div>

            <p class="card-eyebrow">MIRË SE U KTHEVE</p>
            <h2>Kyçu në llogari</h2>

            <p class="card-description">
                Shkruaj të dhënat për të vazhduar.
            </p>

            <?php if ($error !== ""): ?>
                <div class="error-message" role="alert">
                    <span>!</span>
                    <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php">

                <div class="form-group">
                    <label for="email">Email adresa</label>

                    <div class="input-wrap">
                        <span class="input-icon">@</span>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            placeholder="ti@kompania.com"
                            autocomplete="username"
                            maxlength="150"
                            value="<?= htmlspecialchars(
                                $_POST["email"] ?? "",
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">

                    <div class="label-row">
                        <label for="password">Fjalëkalimi</label>
                        <span class="secure-label">🔒 I sigurt</span>
                    </div>

                    <div class="input-wrap">
                        <span class="input-icon">⌑</span>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="Shkruaj fjalëkalimin"
                            autocomplete="current-password"
                            required
                        >

                        <button
                            type="button"
                            class="show-password"
                            id="togglePassword"
                            aria-label="Shfaq fjalëkalimin"
                            aria-pressed="false"
                        >Shfaq</button>
                    </div>
                </div>

                <button type="submit" class="submit-button">
                    <span>Kyçu në TaskFlow</span>
                    <span class="button-arrow">→</span>
                </button>

            </form>

            <div class="divider">
                <span></span>
                <p>QASJE E AUTORIZUAR</p>
                <span></span>
            </div>

            <div class="access-info">
                <div class="access-icon">♙</div>
                <div>
                    <strong>Administrator & Punëtor</strong>
                    <p>Hyrja drejtohet automatikisht sipas rolit.</p>
                </div>
            </div>

            <p class="register-footer">
                Ende nuk je pjesë e ekipit?
                <a href="register.php">Krijo llogari</a>
            </p>

        </section>

    </main>

    <footer class="footer">
        <span>© <?= date("Y") ?> TaskFlow</span>
        <span><i></i> Platformë për organizimin e punës</span>
    </footer>

</div>

<script>
const toggle = document.getElementById("togglePassword");
const passwordInput = document.getElementById("password");

toggle.addEventListener("click", function () {
    const show = passwordInput.type === "password";

    passwordInput.type = show ? "text" : "password";
    toggle.textContent = show ? "Fshih" : "Shfaq";

    toggle.setAttribute("aria-pressed", String(show));

    toggle.setAttribute(
        "aria-label",
        show ? "Fshih fjalëkalimin" : "Shfaq fjalëkalimin"
    );
});
</script>

</body>
</html>
