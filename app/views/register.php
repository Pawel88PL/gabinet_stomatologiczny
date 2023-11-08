<?php
// Na początku register_view.php
$first_name = $first_name_err = "";
$last_name = $last_name_err = "";
$email = $email_err = "";
$password = $password_err = ""; // Jeśli używasz $password w widoku, co zazwyczaj się nie robi z hasłami

// Następnie sprawdź, czy $viewData zostało ustawione
if (isset($viewData)) {
    $first_name = $viewData['first_name'];
    $first_name_err = $viewData['first_name_err'];
    $last_name = $viewData['last_name'];
    $last_name_err = $viewData['last_name_err'];
    $email = $viewData['email'];
    $email_err = $viewData['email_err'];
    // $password nie jest zazwyczaj potrzebne, ponieważ nie powinieneś wyświetlać hasła w formularzu
    $password_err = $viewData['password_err'];
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja</title>
    <link href="../../public/css/styles.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="register-form">
        <h2>Formularz rejestracji</h2>
        <form action="../controllers/register_controller.php" method="post">
            <div class="form-group">
                <label>Imię</label>
                <input type="text" name="first_name" class="form-control" value="<?php echo $first_name; ?>">
                <span class="help-block"><?php echo $first_name_err; ?></span>
            </div>
            <div class="form-group">
                <label>Nazwisko</label>
                <input type="text" name="last_name" class="form-control" value="<?php echo $last_name; ?>">
                <span class="help-block"><?php echo $last_name_err; ?></span>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <label>Hasło</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
        </form>
    </div>
</body>

</html>