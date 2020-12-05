<?php
    declare(strict_types = 1);
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    $usernameError = '';
    $passwordError = '';
    
    function sanitizeInput($value) {
        return htmlspecialchars( stripslashes( trim( $value ) ) );
    }// end sanitizeInput()

    function checkPassword($password) {
       
        if(strlen($password) < 5 || strlen($password) > 20) {
            $error =  "New password does not comply with password policy | Minimum length of 5 characters
            and and maximum length of 20";
            return $error;
        }
        else {
            return '';
        }
    }

    function checkUsername($username) {
        $invalid_characters = preg_match('/\W/', $username);
        if($username == "" || strlen($username) < 5 || strlen($username) > 15 || $invalid_characters) {
            return $error = "New username does not comply with username policy | Minimum length of 5 characters and maximum length of 15
            and allowing only allow letters, numbers, and underscores";
        }
        else {
            return '';
        }
    }


    // If posting then create account
    if ( $_SERVER['REQUEST_METHOD'] == "POST" ) {
        require_once 'inc.db.php';

        // Get the user credentials. Assume values are given.
        $loginUsername = sanitizeInput($_POST['username']);
        $loginPassword = sanitizeInput($_POST['password']);

        $usernameError = checkUsername($loginUsername);
        $passwordError = checkPassword($loginPassword);

        if ($usernameError === '' & $passwordError === '') {
        
            // Hash the password.
            $passwordDigest = password_hash($loginPassword, PASSWORD_BCRYPT);
            
            try {
                $pdo = new PDO(CONNECT_MYSQL, USER, PWD);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $sql = 'INSERT INTO User ' .
                '  (username, password) ' .
                'VALUES ' .
                "  ('$loginUsername', '$passwordDigest')";
                
                if ( $pdo->exec($sql) ) {
                    echo '<p>Your account has been created.</p>',
                    '<p><a href="loginForm.php">Login</a></p>';
                    $pdo = null;
                    die;
                } else {
                    die("Sorry, could not create your account.");
                }
            } catch (PDOException $e) {
                die ( $e->getMessage() );
            }    
        }
    }
?>

<!DOCTYPE html>

<html>
    <head>
        <title>Sign Up Form</title>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    </head>

    <body>
    <body class="w3-container w3-margin-left">
        <div class="w3-card w3-light-gray">
            <header class="w3-container w3-blue w3-margin-top">
                <h1>Create Account</h1>
            </header>

            <form id='form' action="createAccount.php" method="POST" class="w3-container">
            <p>
                    <label class="w3-text-dark-grey">Username</label>
                    <span class="w3-text-red"> *</span>
                    <input required name="username" id="username" placeholder="username" maxlength="25" class="w3-input w3-border">
                    <span id="username-output" style="color:red"> <?php echo $usernameError; ?></span>
                </p>
                <p>
                    <label class="w3-text-dark-grey">Password</label>
                    <span class="w3-text-red"> *</span>
                    <input required type="password" name="password" id="password" placeholder="password" class="w3-input w3-border">
                    <span id="password-output" style="color:red"> <?php echo $passwordError; ?></span>
                </p>
                <p> 
                    <input type="submit" name="submit" value="Create Account" class="w3-btn w3-blue"></input>
                    <span style="float:right;">
                        <a href="loginForm.php">Login</a>
                    </span>
                </p>
            </form>

        </div>
        <script src="js/createAccount.js"></script>
    </body>
</html>