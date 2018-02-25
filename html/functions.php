<?php require "dbconf.php"; ?>
<?php

//Setup Database Connection
$conn = new mysqli($host, $username, $password, $db_name);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


function getUsername() {
  return $_SESSION['username'];
}

function getID() {
  global $conn;
  $sql = "SELECT id FROM members WHERE username = '".$_SESSION['username']."' LIMIT 1";
  $result = $conn->query($sql);
  while($row = $result->fetch_assoc()) {
          return $row["id"];
  }
  $conn->close();
    }

function dashboardCasesTable() {
  global $conn;
  $sql = "SELECT * FROM `cases`  ORDER  BY id DESC LIMIT 10";
  $result = $conn->query($sql);
  while($row = $result->fetch_assoc()) {
    if ($row['complete']==0) {
      $status="in progress";
    }
    else{
      $status=matchCount($row['id']);
    }
          echo "<tr>";
          echo "<td><a href='case.php?id=".$row['id']."'>".$row['title']."</td>";
          echo "<td>".$row['user']."</td>";
          echo "<td>".$status."</td>";
          echo "<tr>";
  }
  $conn->close();
      }

function caseCount($user) {
    $sql2 = "SELECT * FROM `cases` WHERE `user` = ''".$user."'";
    $result2 = $conn->query($sql2);
    $count=0;
    while($row2 = $result2->fetch_assoc()) {
      $count++;
    }
    $conn->close();
    return count;
  }
  function caseDropdown($user) {
    global $conn;
    $sql3 = "SELECT * FROM `cases` WHERE `user` = '".$user."'";
    $result3 = $conn->query($sql3);
    while($row3 = $result3->fetch_assoc()) {
      echo "<li><a href='case.php?id=".$row3['id']."'>Case ".$row3['id']."</a></li>";
    }
    $conn->close();
        }

  function auditTable() {
    global $conn;
    $sql = "SELECT * FROM `members`";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$row['username']."</td>";
            echo "<td>
            <div class='btn-group>'
              <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                ".caseCount($row['username'])." Cases <span class='caret'></span>
              </button>
              <ul class='dropdown-menu'>
                ".caseDropdown($row['username'])."
              </ul>
            </div>
            </td>";
            echo "<tr>";
    }
    $conn->close();
        }

  function fullCasesTable() {
    global $conn;
    $sql = "SELECT * FROM `cases`  ORDER  BY id DESC";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
      if ($row['complete']==0) {
        $status="in progress";
      }
      else{
        $status=matchCount($row['id']);
      }
            echo "<tr>";
            echo "<td><a href='case.php?id=".$row['id']."'>".$row['title']."</td>";
            echo "<td>".$row['user']."</td>";
            echo "<td>".$status."</td>";
            echo "<tr>";
    }
    $conn->close();
        }


  function addCase($title, $notes, $img_path, $user) {
    global $conn;
    $sql = "INSERT INTO `cases` (`id`, `title`, `notes`, `img_path`, `user`, `creation_time`, `complete`) VALUES (NULL, '".$title."', '".$notes."', '".$img_path."', '".$user."', CURRENT_TIMESTAMP, '0');
";
    $result = $conn->query($sql);
    $conn->close();
        }

  function jobsTable() {
    global $conn;
    $sql = "SELECT * FROM `cases` WHERE `complete` = 0 ORDER  BY id DESC";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td><a href='case.php?id=".$row['id']."'>".$row['title']."</td>";
            echo "<td>".$row['user']."</td>";
            echo "<td>".$row['creation_time']."</td>";
            echo "<td><div class='loader'></div></td>";
            echo "<tr>";
    }
    $conn->close();
        }

  function matchCount($id) {
    global $conn;
    $sql = "SELECT * FROM `matches` WHERE `case_num` = ".$id;
    $result = $conn->query($sql);
    $count = 0;
    while($row = $result->fetch_assoc()) {
      $count++;
        }
    $conn->close();
    return $count;
        }

  function newCaseID(){
    global $conn;
    $sql = "SELECT `id` FROM `cases` ORDER  BY id DESC LIMIT 1";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
      return ($row["id"]+1);        }
    $conn->close();
  }

function getEmail() {
  global $conn;
  $sql = "SELECT email FROM members WHERE username = '".$_SESSION['username']."' LIMIT 1";
  $result = $conn->query($sql);
  while($row = $result->fetch_assoc()) {
          return $row["email"];
  }
  $conn->close();
    }
?>
