
<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

$name = $_SESSION["full_name"] ?? "Përdorues";
$role = $_SESSION["role"] ?? "worker";

$isAdmin = ($role === "admin");
$initial = mb_strtoupper(mb_substr($name, 0, 1, "UTF-8"), "UTF-8");
?>
<!DOCTYPE html>
<html lang="sq">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>TaskFlow | Dashboard</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
:root {
    --bg: #090e1b;
    --sidebar: #0e1526;
    --card: #131d32;
    --card-hover: #192641;
    --text: #f1f5ff;
    --muted: #8492ad;
    --accent: #7775ff;
    --accent2: #a78bfa;
    --border: rgba(255,255,255,.07);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    background: var(--bg);
    color: var(--text);
    min-height: 100vh;
}

.layout {
    display: flex;
    min-height: 100vh;
}

/* SIDEBAR */

.sidebar {
    width: 260px;
    background: var(--sidebar);
    border-right: 1px solid var(--border);
    padding: 30px 20px;
    display: flex;
    flex-direction: column;
    position: fixed;
    height: 100vh;
    z-index: 10;
}

.brand {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 45px;
    padding-left: 10px;
}

.brand-icon {
    width: 42px;
    height: 42px;
    background: linear-gradient(135deg,#7775ff,#b18cff);
    border-radius: 13px;
    display: grid;
    place-items: center;
    font-size: 20px;
    box-shadow: 0 5px 25px #7775ff44;
}

.brand h2 {
    font-size: 21px;
    font-weight: 800;
}

.brand span {
    color: var(--accent2);
}

.menu-label {
    font-size: 11px;
    color: #65728d;
    letter-spacing: 2px;
    margin: 15px 10px;
    font-weight: 700;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px;
    color: var(--muted);
    text-decoration: none;
    border-radius: 12px;
    margin-bottom: 7px;
    transition: .25s;
    font-size: 13px;
    font-weight: 600;
}

.nav-link:hover,
.nav-link.active {
    color: white;
    background: linear-gradient(90deg,#7775ff33,#7775ff0a);
    border: 1px solid #7775ff33;
}

.nav-link i {
    width: 18px;
    text-align: center;
}

.sidebar-bottom {
    margin-top: auto;
}

.profile-mini {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px 8px;
    border-top: 1px solid var(--border);
}

.avatar {
    width: 42px;
    height: 42px;
    background: linear-gradient(135deg,#7775ff,#c084fc);
    display: grid;
    place-items: center;
    border-radius: 50%;
    font-weight: 800;
}

.profile-mini strong {
    font-size: 12px;
    display: block;
}

.profile-mini small {
    color: var(--muted);
    font-size: 10px;
}

/* MAIN */

.main {
    margin-left: 260px;
    width: calc(100% - 260px);
    padding: 35px 45px;
}

.topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 40px;
}

.topbar small {
    color: var(--muted);
}

.topbar h2 {
    font-size: 22px;
    margin-top: 5px;
}

.top-actions {
    display: flex;
    align-items: center;
    gap: 15px;
}

.icon-btn {
    width: 42px;
    height: 42px;
    border: 1px solid var(--border);
    background: var(--card);
    color: white;
    border-radius: 12px;
    cursor: pointer;
}

.icon-btn:hover {
    background: var(--card-hover);
}

.welcome {
    padding: 35px;
    border-radius: 22px;
    background:
    radial-gradient(circle at 90% 20%,#a78bfa44,transparent 35%),
    linear-gradient(120deg,#292c64,#171d3b 75%);
    border: 1px solid #ffffff12;
    margin-bottom: 30px;
    position: relative;
    overflow: hidden;
}

.welcome h1 {
    font-size: clamp(25px,4vw,34px);
    margin-bottom: 12px;
}

.welcome p {
    color: #c0c7e5;
    font-size: 14px;
    line-height: 1.7;
}

.welcome-tag {
    display: inline-block;
    background: #ffffff19;
    border: 1px solid #ffffff25;
    padding: 7px 12px;
    border-radius: 30px;
    font-size: 11px;
    margin-bottom: 18px;
}

/* STATISTICS */

.section-title {
    font-size: 17px;
    margin: 30px 0 18px;
}

.stats {
    display: grid;
    grid-template-columns: repeat(4,1fr);
    gap: 18px;
}

.stat-card {
    background: var(--card);
    padding: 22px;
    border: 1px solid var(--border);
    border-radius: 18px;
    transition: .3s;
}

.stat-card:hover {
    transform: translateY(-5px);
    background: var(--card-hover);
    border-color: #7775ff66;
}

.stat-icon {
    width: 43px;
    height: 43px;
    display: grid;
    place-items: center;
    border-radius: 13px;
    margin-bottom: 20px;
    font-size: 17px;
}

.purple { background: #7775ff22; color: #9997ff; }
.green { background: #22c55e22; color: #4ade80; }
.orange { background: #f59e0b22; color: #fbbf24; }
.blue { background: #38bdf822; color: #38bdf8; }

.stat-card h3 {
    font-size: 28px;
    margin-bottom: 7px;
}

.stat-card p {
    color: var(--muted);
    font-size: 12px;
}

/* CONTENT */

.content-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 20px;
    margin-top: 25px;
}

.panel {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 24px;
}

.panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.panel-header h3 {
    font-size: 15px;
}

.panel-header span {
    font-size: 11px;
    color: var(--accent2);
}

.empty-state {
    text-align: center;
    padding: 35px 10px;
    color: var(--muted);
}

.empty-state i {
    font-size: 35px;
    color: #7775ff;
    margin-bottom: 18px;
}

.empty-state h4 {
    color: white;
    margin-bottom: 9px;
}

.empty-state p {
    font-size: 12px;
    line-height: 1.7;
}

/* ACTIVITY */

.activity {
    display: flex;
    gap: 14px;
    margin-bottom: 23px;
}

.activity-icon {
    width: 35px;
    height: 35px;
    border-radius: 10px;
    background: #7775ff22;
    color: #aaa8ff;
    display: grid;
    place-items: center;
    flex-shrink: 0;
}

.activity strong {
    font-size: 12px;
    display: block;
    margin-bottom: 6px;
}

.activity small {
    color: var(--muted);
    font-size: 11px;
}

.logout {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 13px;
    text-decoration: none;
    color: #fb7185;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 600;
    transition: .2s;
}

.logout:hover {
    background: #fb718511;
}

/* FOOTER */

.footer {
    color: #58657e;
    font-size: 11px;
    text-align: center;
    padding: 35px 0 10px;
}

/* RESPONSIVE */

@media(max-width:1100px) {
    .stats {
        grid-template-columns: repeat(2,1fr);
    }
}

@media(max-width:800px) {
    .sidebar {
        width: 75px;
        padding: 25px 10px;
    }

    .brand {
        justify-content: center;
        padding: 0;
    }

    .brand h2,
    .nav-link span,
    .menu-label,
    .profile-mini div:last-child {
        display: none;
    }

    .nav-link {
        justify-content: center;
        padding: 15px 5px;
    }

    .nav-link i {
        font-size: 17px;
    }

    .profile-mini {
        justify-content: center;
    }

    .main {
        margin-left: 75px;
        width: calc(100% - 75px);
        padding: 25px 18px;
    }

    .content-grid {
        grid-template-columns: 1fr;
    }
}

@media(max-width:480px) {
    .stats {
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .stat-card {
        padding: 15px;
    }

    .stat-card h3 {
        font-size: 23px;
    }

    .welcome {
        padding: 25px 20px;
    }

    .topbar h2 {
        font-size: 17px;
    }
}
</style>
</head>

<body>

<div class="layout">

<!-- SIDEBAR -->

<aside class="sidebar">

    <div class="brand">
        <div class="brand-icon">
            <i class="fas fa-check-double"></i>
        </div>
        <h2>Task<span>Flow</span></h2>
    </div>

    <div class="menu-label">MENU KRYESORE</div>

    <a href="dashboard.php" class="nav-link active">
        <i class="fas fa-house"></i>
        <span>Dashboard</span>
    </a>

    <a href="tasks.php" class="nav-link">
        <i class="fas fa-list-check"></i>
        <span>Detyrat</span>
    </a>

    <?php if ($isAdmin): ?>
    <div class="menu-label">ADMINISTRIMI</div>

    <a href="users.php" class="nav-link">
        <i class="fas fa-users"></i>
        <span>Përdoruesit</span>
    </a>

    <a href="reports.php" class="nav-link">
        <i class="fas fa-chart-line"></i>
        <span>Raportet</span>
    </a>
    <?php endif; ?>

    <div class="menu-label">LLOGARIA</div>

    <a href="profile.php" class="nav-link">
        <i class="fas fa-user-circle"></i>
        <span>Profili im</span>
    </a>

    <a href="settings.php" class="nav-link">
        <i class="fas fa-gear"></i>
        <span>Cilësimet</span>
    </a>

    <div class="sidebar-bottom">

        <a href="logout.php" class="logout">
            <i class="fas fa-right-from-bracket"></i>
            <span>Dil nga llogaria</span>
        </a>

        <div class="profile-mini">
            <div class="avatar">
                <?= htmlspecialchars($initial, ENT_QUOTES, "UTF-8") ?>
            </div>

            <div>
                <strong>
                    <?= htmlspecialchars($name, ENT_QUOTES, "UTF-8") ?>
                </strong>
                <small><?= $isAdmin ? "Administrator" : "Punëtor" ?></small>
            </div>
        </div>

    </div>
</aside>

<!-- MAIN CONTENT -->

<main class="main">

    <div class="topbar">
        <div>
            <small>Mirë se erdhe në hapësirën tënde</small>
            <h2>Dashboard Overview</h2>
        </div>

        <div class="top-actions">
            <button class="icon-btn" title="Njoftimet">
                <i class="far fa-bell"></i>
            </button>

            <button class="icon-btn" id="themeBtn" title="Ndrysho pamjen">
                <i class="fas fa-moon"></i>
            </button>
        </div>
    </div>

    <!-- WELCOME -->

    <section class="welcome">

        <div class="welcome-tag">
            <i class="fas fa-circle-check"></i>
            SISTEMI ËSHTË AKTIV
        </div>

        <h1>Mirë se erdhe, <?= htmlspecialchars($name, ENT_QUOTES, "UTF-8") ?>! 👋</h1>

        <?php if ($isAdmin): ?>
            <p>
                Mirë se erdhe në panelin e administratorit.
                Këtu mund të menaxhosh ekipin, përdoruesit,
                detyrat dhe aktivitetet e sistemit.
            </p>
        <?php else: ?>
            <p>
                Ky është paneli yt personal.
                Ndiq detyrat, organizo punën dhe qëndro
                gjithmonë në hap me aktivitetet e tua.
            </p>
        <?php endif; ?>

    </section>

    <!-- STATISTICS -->

    <h3 class="section-title">Statistikat e sistemit</h3>

    <div class="stats">

        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-list-check"></i>
            </div>
            <h3>--</h3>
            <p><?= $isAdmin ? "Detyra gjithsej" : "Detyrat e mia" ?></p>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3>--</h3>
            <p>Detyra të përfunduara</p>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-clock"></i>
            </div>
            <h3>--</h3>
            <p>Detyra në pritje</p>
        </div>

        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-users"></i>
            </div>
            <h3><?= $isAdmin ? "--" : "1" ?></h3>
            <p><?= $isAdmin ? "Anëtarë të ekipit" : "Llogaria ime" ?></p>
        </div>

    </div>

    <!-- CONTENT -->

    <div class="content-grid">

        <section class="panel">

            <div class="panel-header">
                <h3><i class="fas fa-list-check"></i> Detyrat e fundit</h3>
                <span>SHIKO TË GJITHA →</span>
            </div>

            <div class="empty-state">
                <i class="fas fa-clipboard-list"></i>
                <h4>Gati për të filluar?</h4>
                <p>
                    Detyrat e tua do të shfaqen këtu.
                    Organizo punën dhe menaxho aktivitetet
                    në një vend të vetëm.
                </p>
            </div>

        </section>

        <section class="panel">

            <div class="panel-header">
                <h3><i class="fas fa-bolt"></i> Aktivitetet</h3>
                <span>LIVE</span>
            </div>

            <div class="activity">
                <div class="activity-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <div>
                    <strong>Kyçje e suksesshme</strong>
                    <small>Mirë se erdhe në TaskFlow</small>
                </div>
            </div>

            <div class="activity">
                <div class="activity-icon">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <div>
                    <strong>Sesioni aktiv</strong>
                    <small>Llogaria jote është e kyçur</small>
                </div>
            </div>

            <div class="activity">
                <div class="activity-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <strong>Roli yt</strong>
                    <small><?= $isAdmin ? "Administrator" : "Punëtor" ?></small>
                </div>
            </div>

        </section>

    </div>

    <div class="footer">
        © <?= date("Y") ?> TaskFlow. Të gjitha të drejtat e rezervuara.
        Ndërtuar me <i class="fas fa-heart" style="color:#fb7185"></i>
    </div>

</main>

</div>

<script>
const themeBtn = document.getElementById("themeBtn");

themeBtn.addEventListener("click", function() {
    const root = document.documentElement;

    if (root.dataset.theme === "light") {
        root.dataset.theme = "dark";
        document.body.style.background = "#090e1b";
        document.body.style.color = "#f1f5ff";
        themeBtn.innerHTML = '<i class="fas fa-moon"></i>';
    } else {
        root.dataset.theme = "light";
        document.body.style.background = "#eef1f8";
        document.body.style.color = "#172033";
        themeBtn.innerHTML = '<i class="fas fa-sun"></i>';
    }
});
</script>

</body>
</html>
