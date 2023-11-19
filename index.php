<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dentlux</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="public/css/styles.css">
    <link rel="stylesheet" href="public/css/index.css">
</head>

<body>
    <?php include 'app/views/shared_navbar.php'; ?>

    <header class="hero-section m-3">
        <div class="container">
            <h1>DENTLUX</h1>
            <h2>Profesjonalna opieka dentystyczna</h2>
            <h1>Zarezerwuj swoją wizytę</h1>
            <a href="/gabinet/app/views/patient_register.php" class="btn btn-primary m-2">Zarejestruj się teraz</a>
            <a href="/gabinet/app/views/patient_login.php" class="btn btn-secondary m-2">Zaloguj się</a>
        </div>
    </header>

    
    <section class="about-section">
        <div class="container">
            <h2>O nas</h2>
            <p>Krótki opis misji i wartości Twojego serwisu medycznego.</p>
        </div>
    </section>


    <!-- Skrypty JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>

</html>