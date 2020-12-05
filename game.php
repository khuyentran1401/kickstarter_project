<?php
  // File: landing.php
  session_start();

  if ( !isset( $_SESSION['username'] ) ) {
    // If username variable is not set, then send them back to the login form.
    header('Location: loginForm.php');
    die;

  }

  function sanitizeInput($value) {
    return htmlspecialchars( stripslashes( trim( $value) ) );
  }
  function selectedStatus(string $selection, string $value) {
    // If selected option matches given value, return 'selected'
    // else return ''.
    return $selection === $value? 'selected' : '';
  }

  function getProjectInformation(string $project, PDO $pdo) {
    $sql = "SELECT DISTINCT name, main_category, deadline, launched, backers, country, goal
          FROM guess_data
          WHERE name = 'Your Own Camera In Africa.'
          ";

  $name = $main_category = $deadline = $launched = $backers = $country = $goal = '';

  $stm = $pdo->query($sql);

  return $stm;
}

  function createNumberHintOptions() {
    $numberHints = range(0,10);
    $numberHint = '';

    $selection = isset($_POST['number'])? sanitizeInput($_POST['number']) : '';
    $numberHint .= "<p>Hints are the information and outcome of other projects </p>
                  <select name='numHints'>, ";
    $numberHint .= '<option selected disabled>Select number of hints</option>, ';

    foreach($numberHints as $number) {
      $isSelected =  selectedStatus($selection, $number);
      $numberHint .= "<p><option value='$number' $isSelected>$number</option>, ";

    }
    $numberHint .= "</select></p>";
    $numberHint .= "<button id=numberSubmit>Submit</button>, ";

    $numberHint = rtrim($numberHint, ', ');

    return $numberHint;
  }


try {

  require_once 'inc.db.php';

  $pdo = new PDO(CONNECT_MYSQL, USER, PWD);

  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $stm = getProjectInformation($_SESSION['project'], $pdo);

  foreach($stm as $project) {
    foreach($project as $key => $val) {
      $name = $project['name'];
      $main_category = $project['main_category'];
      $deadline = $project['deadline'];
      $launched = $project['launched'];
      $backers = $project['backers'];
      $country = $project['country'];
      $goal = '$' . $project['goal'];
    }
  }

  $pdo = null;
} catch(PDOException $e) {
  // Handle and exceptions.
  die ($e->getMessage());
}


if ( $_SERVER['REQUEST_METHOD'] == "POST" ) {

  if (isset($_POST["hint"])) {

    $numberHint = createNumberHintOptions();
  }

  if (isset($_POST["another-project"])) {

    header('Location: landing.php');
  }

  if ( isset($_POST['numHints']) ) {

    $_SESSION['numHints'] = sanitizeInput($_POST['numHints']);

    header('Location: hint.php');
    die;
}
}



?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Project Information</title>
        <link href="https://www.w3schools.com/w3css/4/w3.css" rel="stylesheet">
    </head>

    <body class="w3-container">
        <header class="w3-center">
            <h1>Project Information</h1>
        </header>

        <main class="w3-container">
              

          <form action="<?php echo $phpScript; ?>" method="POST" class="w3-container">

                <p class="w3-panel">
                    <label>Project </label>
                    <input class="w3-input w3-border" placeholder="Project" value="<?php echo $name; ?>" readonly>
                </p>

                <p class="w3-panel"> <!-- Country population -->
                    <label>Main Category</label>
                    <input class="w3-input w3-border" placeholder="Main Category"  value="<?php echo $main_category; ?>" readonly>
                </p>

                <p class="w3-panel"> 
                    <label>Deadline</label>
                    <input class="w3-input w3-border" placeholder="Deadline"  value="<?php echo $deadline; ?>"readonly>
                </p>
                <p class="w3-panel"> 
                    <label>Launched</label>
                    <input class="w3-input w3-border" placeholder="Launched"  value="<?php echo $launched; ?>"readonly>
                </p>
                <p class="w3-panel"> 
                    <label>Backers</label>
                    <input class="w3-input w3-border" placeholder="Backers"  value="<?php echo $backers; ?>"readonly>
                </p>
                <p class="w3-panel"> 
                    <label>Country</label>
                    <input class="w3-input w3-border" placeholder="Country"  value="<?php echo $country; ?>"readonly>
                </p>
                <p class="w3-panel"> 
                    <label>Goal</label>
                    <input class="w3-input w3-border" placeholder="Goal"  value="<?php echo $goal; ?>"readonly>
                </p>
                <p>
                <button name="hint" class="w3-btn w3-red">Add hint</button>
                <button name='another-project' style="float: right;" class="w3-btn w3-blue">Choose to another project</button>
        </form>
        <div align="center">
          <form id=numberHint action="<?php echo $phpScript; ?>" method="POST">
                
                <?php echo $numberHint; ?>
          
          </form>
        </div>

        <footer class="w3-panel w3-center w3-text-grey w3-small">
        </footer>
    </body>
</html>