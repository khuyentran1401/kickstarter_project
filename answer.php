<?php
  // File: landing.php
  session_start();

  if ( !isset( $_SESSION['username'] ) ) {
    // If username variable is not set, then send them back to the login form.
    header('Location: loginForm.php');
    die;

  }

  function getAnswer(string $guess, string $project, PDO $pdo) {
      $sql = "SELECT state 
            FROM guess_data
            WHERE name = '$project'";

      $stm = $pdo->query($sql, PDO::FETCH_ASSOC);

      if ($guess == 'yes') {
        $guess = 'successful';
      }
      else {
        $guess = 'failed';
      }


      foreach($stm as $key=>$value) {
        foreach($value as $val) {
          $real_answer = $val;
        }
      }

      return $real_answer;
    }

    function getResult($real_answer, $guess) {

      if ($real_answer == $guess) {

        $result = "successful";
      }
      else {
        $result = "failed";
      }

    return $result;
    
  }

  function findProjectId(string $project, PDO $pdo) {
    $sql = "SELECT id 
          FROM guess_data 
          WHERE name = '$project'";

    $stm = $pdo->query($sql, PDO::FETCH_ASSOC);

    foreach ($stm as $key=>$value) {
        foreach ($value as $k=>$v) {
            return $v;
        }
    }
}

  function findUserId(string $username, PDO $pdo) {
    $sql = "SELECT id FROM User WHERE username = '$username'";

    $stm = $pdo->query($sql, PDO::FETCH_ASSOC);

    foreach ($stm as $key=>$value) {
        foreach ($value as $k=>$v) {
            return $v;
        }
    }
  }

  function addToUserRecord($userid, $projectId, $guess, $real_answer, $result, $pdo) {
    $sql = "INSERT INTO user_result
            VALUES ($userid, $projectId, '$guess', '$real_answer', '$result', '')";

    $stm = $pdo->query($sql);

  } 

  try {

    require_once 'inc.db.php';
  
    $pdo = new PDO(CONNECT_MYSQL, USER, PWD);
  
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $guess = $_SESSION['choice'];
    $project = $_SESSION['project'];
    $numHints = $_SESSION['numHints'];

    $answer = getAnswer($guess, $project, $pdo);
    $result = getResult($real_answer, $guess);

    if ($result == 'successful') {
        
      $resultStatement = "Congratulations! You predicted correctly with $numHints hints. This project is $real_answer.";
    }
    else {
      $resultStatement = "Your prediction is incorrect, but don't feel discouraged. Try it again!";
    }

    $userId = findUserId($_SESSION['username'], $pdo);
    $projectId = findProjectId($project, $pdo);

    addToUserRecord($userId, $projectId, $guess, $answer, $result, $pdo);

    $_SESSION['userId'] = $userId;


  
    $pdo = null;
  } catch(PDOException $e) {
    // Handle and exceptions.
    die ($e->getMessage());
  }

  if ( $_SERVER['REQUEST_METHOD'] == "POST" ) {
    if (isset($_POST['tryAgain'])) {
      header('Location: landing.php');
    }
  }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Result</title>
        <link href="https://www.w3schools.com/w3css/4/w3.css" rel="stylesheet">
    </head>


    <body id="index_page" class="w3-container">

      <main id="content" class="w3-panel">
        <h1>Result</h1>
        <form id=project action="answer.php" method='POST'>
          <p name=result> <?php echo $resultStatement ?> </p>
          <button name=tryAgain>Try Again</button>
        </form>
        <p> View all your guesses and results <a href='record.php'>here</a></p>
      </main>

      <footer class="w3-panel w3-center w3-text-gray w3-small">
      </footer>
    </body>
</html>
