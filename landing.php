<?php
  // File: landing.php
  session_start();

  if ( !isset( $_SESSION['username'] ) ) {
    // If username variable is not set, then send them back to the login form.
    header('Location: loginForm.php');
    die;
  }


  function sanitizeInput($value) {
    return htmlspecialchars( stripslashes( trim( $value ) ) );
  }

  function selectedStatus(string $selection, string $value) {
    // If selected option matches given value, return 'selected'
    // else return ''.
    return $selection === $value? 'selected' : '';
}
  function checkedStatus(string $radio, string $value) {
    // If the radio clicked matches the given value, then
    // return 'checked', else return ''.
    return $radio === $value? 'checked' : '';
  }

  function buildCategoryOptions(PDO $pdo) {

    $sql = "SELECT DISTINCT category FROM category";

    $stm = $pdo->query($sql);

    $selection = '';
    $categories = '';

    $selection = isset($_POST['category'])? sanitizeInput($_POST['category']) : '';
    
    foreach($stm as $category) {
        $isSelected =  selectedStatus($selection, $category[0]);
        $categories .= "<option value='$category[0]' $isSelected>$category[0]</option>, ";
        
    }

    $categories = rtrim($categories, ', ');

    return $categories;
  }

  function showProjects(string $category, PDO $pdo) {

    $sql = "SELECT g.name
          FROM category AS c, guess_data as g 
          WHERE c.category = '$category'
          AND c.project_id = g.id
          ORDER BY RAND()
          LIMIT 20;
          ";

    $stm = $pdo->query($sql);

    $radioOptions = '';
    $selection = '';

    $selection = isset($_POST['project'])? sanitizeInput($_POST['project']) : '';

    $radioOptions .= "<p>Choose one project that you want to guess</p>";
    foreach($stm as $project) {
      $isChecked = checkedStatus($selection, $project[0]);
      $radioOptions .= "<input type='radio' name='project' value='$project[0]' $isChecked> $project[0] <br>";
    }

    $radioOptions .= "<button>Submit</button>";

    return $radioOptions;

  }

  try {

    require_once 'inc.db.php';

    $pdo = new PDO(CONNECT_MYSQL, USER, PWD);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $categoryOptions = buildCategoryOptions($pdo);

    $pdo = null;
  } catch(PDOException $e) {
    // Handle and exceptions.
    die ($e->getMessage());
  }

  if ( $_SERVER['REQUEST_METHOD'] == "POST" ) {


    if ( isset($_POST['project'])) {
        $_SESSION['project'] = sanitizeInput($_POST['project']);
        header('Location: game.php');
    }


    if ( (isset($_POST['category'])) & (!(isset($_POST['project'])))) {
      // extract all values.
      $category = sanitizeInput($_POST['category']);

      try {

        require_once 'inc.db.php';
    
        $pdo = new PDO(CONNECT_MYSQL, USER, PWD);
    
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        $radioOptions = showProjects($category, $pdo);
    
        $pdo = null;
      } catch(PDOException $e) {
        // Handle and exceptions.
        die ($e->getMessage());
      }
        
    }


    } else {
      $selection = "Error-Select an option";
    }


?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home page</title>
        <link href="https://www.w3schools.com/w3css/4/w3.css" rel="stylesheet">
    </head>


    <body id="index_page" class="w3-container">
      <header>
        <h1>Guessing Game</h1>
        <h3>Test your judgement by deciding whether a project will reach its goal</h3>
      </header>

      <main id="content" class="w3-panel">
          <p><b>Background: </b>Kickstarter is an American public benefit corporation that maintains a global crowdfunding platform focused on creativity. As of December 2019, Kickstarter has received more than $4.6 billion in pledges from 17.2 million backers to fund 445,000 projects, such as films, music, stage shows, comics, journalism, video games, technology, publishing, and food-related projects. People who back Kickstarter projects are offered tangible rewards or experiences in exchange for their pledges.<br></p>
          <p>Project creators choose a deadline and a minimum funding goal. If the goal is not met by the deadline, no funds are collected</p>
          <p><b>Objective: </b>A project can be successful or failed. If the project did not reach its goal by the deadline, it is considered a failed project. In this game, you will use the information provided about a project to decide whether it will be successful or not. 
          You can look up hints that contain the information and outcome of other projects in order to guess the outcome of your chosen project. Have fun!</p>
          <form id=category action="landing.php" method="POST">
              <p><select name="category">
                  <option selected disabled>Select a category</option>
                  <?php echo $categoryOptions; ?>
              </select></p>

              <button>Submit</button>
          </form>
          <form id=project action="landing.php" method="POST">
            <p name='projects'>
              <?php echo $radioOptions; ?>
            </p>
          </form>
      </main>

      <footer class="w3-panel w3-center w3-text-gray w3-small">
      </footer>
    </body>
</html>
