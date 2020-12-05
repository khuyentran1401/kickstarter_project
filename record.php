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


function getUserRecord(int $userId, $pdo) {
    $sql = "SELECT g.name, g.main_category, g.deadline, g.launched, g.backers, g.country, g.pledged, g.goal, r.guess, r.real_outcome, r.is_right_prediction, r.observation
    FROM guess_data as g, user_result as r 
    WHERE r.userId = $userId
    AND r.projectId = g.id;";

    $stm = $pdo->query($sql, PDO::FETCH_ASSOC);

    $table = '';

    $table .= "<body>
    <table style='width:100%'>
        <thead>
            <tr>
                <th>Name</th>
                <th>Main Category</th>
                <th>Deadline</th>
                <th>Launched Date</th>
                <th>Backers</th>
                <th>Country</th>
                <th>Pledged</th>
                <th>Goal</th>
                <th>Guess</th>
                <th>Real outcome</th>
                <th>Is right prediction</th>
            </tr>
        </thead>
        <tbody>";

    $i = 0;
    foreach($stm as $row) {
        
        $table .= "
            <tr>
                <td> {$row['name']} </td>
                <td> {$row['main_category']} </td>
                <td> {$row['deadline']} </td>
                <td> {$row['launched']} </td>
                <td> {$row['backers']} </td>
                <td> {$row['country']} </td>
                <td> {$row['pledged']} </td>
                <td> {$row['goal']} </td>
                <td> {$row['guess']} </td>
                <td> {$row['real_outcome']} </td>
                <td> {$row['is_right_prediction']} </td>
            </tr>
        ";
        $i += 1;
            
    }

    $table .= "</tbody>
    </table>
    </body>";

    return $table;
}

// function updateRecord()

$main_category = $observation = $is_right_prediction = $real_outcome = $guess = $goal = $pledged = $country = $backers = $launched = $deadline = $project = $main_category = '';
$userId = $_SESSION['userId'];

try {
    require_once 'inc.db.php';
    $pdo = new PDO(CONNECT_MYSQL, USER, PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $table = getUserRecord($userId, $pdo);
    
} catch (PDOException $e) {
    die ( $e->getMessage() );
}    

if ( $_SERVER['REQUEST_METHOD'] == 'POST') {    
    
    if (isset($_POST['submit'])) {
            header('Location: landing.php');
        }
  }
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Your record</title>
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
            <h1>Your record</h1>
            
        </header>

        <main class="w3-container">
              

          <form id='result_form' action="<?php echo $phpScript; ?>" method="POST" class="w3-container"> 
            <?php echo $table; ?>
            <button name="submit" form='result_form' class="w3-btn w3-red">Guess another project</button>
            <!-- <span style="float:right;"> -->
            &nbsp;&nbsp;&nbsp;
            <!-- <a href='/project/landing.php'>Guess another project</a> -->
                <!-- </span> -->
            </form>
        <footer class="w3-panel w3-center w3-text-grey w3-small">
        </footer>
    </body>
</html>