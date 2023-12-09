<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container-fluid">
        <a class="btn btn-light btn-lg" href="/gabinet/index.php">DENTLUX</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="btn btn-light" href="/gabinet/app/views/patient_register.php">Zarejestruj się!</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="btn btn-light dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Twoje konto
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="/gabinet/app/views/patient_panel.php">Panel pacjenta</a></li>
                        <li><a class="dropdown-item" href="/gabinet/app/views/dentist_panel.php">Panel dentysty</a></li>
                        <li><a class="dropdown-item" href="/gabinet/app/views/admin_panel.php">Panel administratora</a></li>
                    </ul>
                </li>
            </ul>
            <span class="nav-item">
                <?php
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                    $firstName = $_SESSION['first_name'] ?? 'Gość';
                    $role = $_SESSION['role'] ?? 'nieokreślona rola';

                    // Tłumaczenie roli na język polski
                    switch ($role) {
                        case 'administrator':
                            $translatedRole = 'administrator';
                            break;
                        case 'patient':
                            $translatedRole = 'pacjent';
                            break;
                        case 'dentist':
                            $translatedRole = 'dentysta';
                            break;
                        default:
                            $translatedRole = 'nieokreślona rola';
                    }

                    echo "Cześć <strong>" . htmlspecialchars($firstName) . "</strong>! Jesteś zalogowany jako <strong>" . htmlspecialchars($translatedRole) . "</strong>.";
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