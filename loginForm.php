<?php
    // File: loginForm.php
    declare(strict_types = 1);

    session_start();    

    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    
    $curYear = date('Y');
    $username = $password = $errorMessage = "";
    $phpScript = sanitizeValue($_SERVER['PHP_SELF']);



    function sanitizeValue($value) {
        return htmlspecialchars( stripslashes( trim( $value ) ) );
    }

    
    // Processing logic.
    if ( $_SERVER['REQUEST_METHOD'] == 'POST') {    
        require_once 'inc.db.php';

        // Retrieve the user record and authenticate it. If successful, track it with sessions,
        // and redirect to welcome page. Else, do not redirect.
        $username = sanitizeValue( $_POST['username']);
        $password = sanitizeValue( $_POST['password'] );

        try {
            $pdo = new PDO(CONNECT_MYSQL, USER, PWD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $sql = "
            SELECT username, password 
            FROM User
            WHERE username = '$username'
            ";

            $stm = $pdo->query($sql, PDO::FETCH_ASSOC);

            
            if ($stm->rowCount() == 1) {
                $pdo = null;
                $userRecord = $stm->fetch();

                if ( password_verify($password, $userRecord['password'])) {
                // if ( $password === $userRecord['password'] ) {
                    $_SESSION['username'] = $username;
                    header('Location: landing.php');
                } else {
                    die("Unable to authenticate.");
        }
           } else {
            die("Sorry, could not verify account.");
           }
        } catch (PDOException $e) {
            die ( $e->getMessage() );
        }
    }
?>

<!DOCTYPE html>

<html>
    <head>
        <title>Login Form</title>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    </head>

    <body>
    <body class="w3-container w3-margin-left">
        <div class="w3-card w3-light-gray">
            <header class="w3-container w3-blue w3-margin-top">
                <h1>Login Form</h1>
            </header>

            <form action="<?php echo $phpScript; ?>" method="POST" class="w3-container">
            <p><!-- username -->
                    <label class="w3-text-dark-grey">Username</label>
                    <span class="w3-text-red"> *</span>
                    <input required name="username" placeholder="username" value="<?php echo $username; ?>" class="w3-input w3-border">
                </p>
                <p><!-- password -->
                    <label class="w3-text-dark-grey">Password</label>
                    <span class="w3-text-red"> *</span>
                    <input required type="password" name="password" placeholder="password" value="<?php echo $password; ?>" class="w3-input w3-border">
                </p>
                <p> <!-- login -->
                    <button name="submit" class="w3-btn w3-blue">Login</button>
                    <span style="float:right;">
                        <a href="createAccount.php">Register</a>
                    </span>
                </p>
            </form>

            <h2 class="w3-container w3-text-red"><?php echo $errorMessage; ?></h2>
        </div>
    </body>
</html>