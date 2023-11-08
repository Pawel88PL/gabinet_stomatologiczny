<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Logowanie</title>
    <!-- Link do plików CSS -->
</head>

<body>
    <div>
        <h2>Logowanie</h2>
        <form action="../controllers/login_controller.php" method="post">
            <div>
                <label>Email</label>
                <input type="email" name="email">
            </div>
            <div>
                <label>Hasło</label>
                <input type="password" name="password">
            </div>
            <div>
                <input type="submit" value="Zaloguj się">
            </div>
            <p>Nie masz konta? <a href="register.php"> Zarejestruj się</a></p>
        </form>
    </div>
</body>

</html>