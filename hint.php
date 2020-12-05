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

  function checkedStatus(string $radio, string $value) {
      // If the radio clicked matches the given value, then
      // return 'checked', else return ''.
      return $radio === $value? 'checked' : '';
      }
  
  function selectedStatus(string $selection, string $value) {
    // If selected option matches given value, return 'selected'
    // else return ''.
    return $selection === $value? 'selected' : '';
  }

  function getHints(int $numberHint, PDO $pdo) {

      $sql = "SELECT name, main_category, deadline, launched, backers, country, goal, pledged, state
              FROM hint_data
              ORDER BY RAND()
              LIMIT 10";
      $stm = $pdo->query($sql);

      $stm->setFetchMode(PDO::FETCH_ASSOC);

      $hints = '';
      
      $hints .= "<body>
          <table style='width:100%'>
              <thead>
                  <tr>
                      <th>Name</th>
                      <th>Main Category</th>
                      <th>Deadline</th>
                      <th>Launched Date</th>
                      <th>Backers</th>
                      <th>Country</th>
                      <th>Goal</th>
                      <th>Pledged</th>
                      <th>State</th>
                  </tr>
              </thead>
              <tbody>";

      foreach($stm as $row) {
        $hints .= "
            <tr>
                <td> {$row['name']} </td>
                <td> {$row['main_category']} </td>
                <td> {$row['deadline']} </td>
                <td> {$row['launched']} </td>
                <td> {$row['backers']} </td>
                <td> {$row['country']} </td>
                <td> {$row['goal']} </td>
                <td> {$row['pledged']} </td>
                <td> {$row['state']} </td>
            </tr>
        ";
      }

      $hints .= " 
          </tbody>
          </table>
          </body>";
      return $hints;
    }

  function createRadioOptions($radio) {

    $radioOptions = '';
    $isCheckedYes = checkedStatus($radio, 'yes');
    $isCheckedNo = checkedStatus($radio, 'no');
    $radioOptions .= "
                <h3>Will this project be successful?</h3>
                <p name='choice'>
                <input type='radio' name='choice' value='yes' $isCheckedYes> yes
                <input type='radio' name='choice' value='no' $isCheckedNo>no
                </p>
                <button id=choiceButton>Submit Answer</button>
                ";

    return $radioOptions;
  }


  $numberSelect = $_SESSION['numHints'];

  try {

    require_once 'inc.db.php';
  
    $pdo = new PDO(CONNECT_MYSQL, USER, PWD);
  
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $hints = getHints($numberSelect, $pdo);
  
    $pdo = null;
  } catch(PDOException $e) {
    // Handle and exceptions.
    die ($e->getMessage());
  }

  $radio = '';
  if ( $_SERVER['REQUEST_METHOD'] == "POST" ) {

      if (isset($_POST['choice'])) {
        $choice = sanitizeInput($_POST['choice']);
        $_SESSION['choice'] = $choice;

        header('Location: answer.php');
      }
      else if (isset($_POST['ready']) ) {

        $radio = sanitizeInput($_POST['radio']);
        $radioOptions = createRadioOptions($radio);

    } 
  }
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hints</title>
        <link href="https://www.w3schools.com/w3css/4/w3.css" rel="stylesheet">
        <style>
            table, th, td {
              border: 1px solid black;        
            }
            th, td {
              padding: 15px;
            }
        </style>
    </head>
    <body class="w3-container">
        <header class="w3-center">
            <h1>Hints</h1>
        </header>

        <main class="w3-container">
            <form action="<?php echo $phpScript; ?>">
                <?php echo $hints; ?>
            </form>
            &ensp;
            <div align='center'>
              <form action="<?php echo $phpScript; ?>" method="POST">
                  <button name='ready' class="w3-btn w3-red">Guess Project</button>
              </form>
            </div>
            <div align='center'>
              <form id=choice action="<?php echo $phpScript; ?>" method="POST">
                
                  <?php echo  $radioOptions?>
              </form>
            </div>
        </main>
        <footer class="w3-panel w3-center w3-text-grey w3-small">
        </footer>
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="js/home.js"></script> -->

    </body>
</html>