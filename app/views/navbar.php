<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container-fluid">
        <a class="btn btn-light btn-lg" href="/gabinet/index.php">DENTLUX</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="btn btn-light" href="/gabinet/app/views/register.php">Rejestracja</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-light" href="/gabinet/app/views/login.php">Logowanie</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-light" href="/gabinet/app/views/patient_panel.php">Panel pacjenta</a>
                </li>
            </ul>
            <span class="nav-item">
                <?php
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                    $firstName = $_SESSION['first_name'] ?? 'Gość';
                    echo 'Witaj <strong>' . htmlspecialchars($firstName) . '!</strong>';
                }
                ?>
            </span>
            <span class="nav-item">
                <?php
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {

                    echo '<a class="btn btn-light" href="/gabinet/app/controllers/logout_controller.php">Wyloguj się</a>';
                }
                ?>

            </span>
        </div>
    </div>
</nav>