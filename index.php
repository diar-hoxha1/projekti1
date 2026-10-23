
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
                    <?= htmlspecialchars(
                        $error,
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>
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
