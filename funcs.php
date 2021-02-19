
<?php
include "./db.php";

function gen_uuid()
{
  return sprintf(
    '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
    // 32 bits for "time_low"
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff),

    // 16 bits for "time_mid"
    mt_rand(0, 0xffff),

    // 16 bits for "time_hi_and_version",
    // four most significant bits holds version number 4
    mt_rand(0, 0x0fff) | 0x4000,

    // 16 bits, 8 bits for "clk_seq_hi_res",
    // 8 bits for "clk_seq_low",
    // two most significant bits holds zero and one for variant DCE1.1
    mt_rand(0, 0x3fff) | 0x8000,

    // 48 bits for "node"
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff)
  );
}

function is_new_game($id) {
  global $conn;
  $sql = "SELECT * FROM games WHERE game_id = '$id'";
  if (mysqli_query($conn, $sql)->num_rows == 0) {
    return true;
  }
  return false;
}

function init_game($id, $data, $status) {
  global $conn;
  $sql = "INSERT INTO `games` (`game_id`, `data`, `status`) VALUES ('$id', '$data', '$status');";
  mysqli_query($conn, $sql);
}

function update_game($id, $data, $status)
{
  global $conn;
  $sql = "UPDATE games SET data = '$data', status = '$status' WHERE game_id = '$id'";
  mysqli_query($conn, $sql);
}
function get_game_data($id) {
  global $conn;
  $sql = "SELECT * from games WHERE game_id = '$id'";
  $result = mysqli_query($conn, $sql);
  $row = $result->fetch_row();
  return $row[1];
}

function gen_data($rows) {
  $result = explode(",", get_game_data($_GET["id"]));
  $result = array_filter($result);
  $result = $result[count($result) - 1];
  $result = explode(".", $result);
  if ($result == [""]) {
    array_shift($result); // supprime empty string
    for ($i = 0; $i < $rows; $i++) {
      for ($j = 0; $j < $rows; $j++) {
        if (random_int(0, 100) < 25) {
          array_push($result, "-");
        } else {
          array_push($result, "0h");
        }
      }
    }
  }
  $c = 0;
  foreach ($result as $index => $value) {
    if ($result[$index] != "-" && $result[$index] != "+") {
      switch ($c) {
        case 0:
          $positions = [$rows * -1, $rows * -1 + 1, 1, $rows, $rows + 1];
          break;
        case $rows - 1:
          $positions = [$rows * -1 - 1, $rows * -1, -1, $rows - 1, $rows];
          break;
        default:
          $positions = [$rows * -1 - 1, $rows * -1, $rows * -1 + 1, -1, 1, $rows - 1, $rows, $rows + 1];
          break;
      }
      $value = intval($value);
      $mines = 0;
      foreach ($positions as  $pos) {
        if (array_key_exists($index + $pos, $result)) {
          if ($result[$index + $pos] == "-") {
            $mines++;
          }
        }
      }
      // show the field the user
      if (isset($_GET["move"]) && $_GET["move"] == $index) {
        $result[$index] = $mines . "s";
      } else if (!str_contains($result[$index], "s")) {
        $result[$index] = $mines . "h";
      }
    } else {
      if (isset($_GET["move"]) && $_GET["move"] == $index) {
        $result[$index] = "+";
        update_game($_GET["id"], get_game_data($_GET["id"]) . str_replace("-", "+", implode(".", $result)) . ",", 0);
        return str_replace("-", "+", $result);
      }
    }
    if ($c == $rows - 1) {
      $c = 0;
    }
  }
  update_game($_GET["id"], get_game_data($_GET["id"]) . implode(".", $result) . ",", 1);
  return $result;
}

function draw_data($data, $size) {
  $id = $_GET["id"];
  $diff = $_GET["diff"];
  $disabled = "";
  if (is_game_over($id) == 1) {
    $disabled = "";
  } else {
    $disabled = "disabled";
  }
  $percent = intval(100 / $size);
  $c = 0;
  echo "<form id='form'>";
  foreach ($data as $index => $val) {
    if ($val == "-" || str_contains($val, "h")) {
      $display = "";
    } 
    else if ($val == "+") {
      $display = "💣";
    }
    else if ($val == "0s") {
      $display = "⓿";
    }
    else if ($val == "1s") {
      $display = "❶";
    }
    else if ($val == "2s") {
      $display = "❷";
    }
    else if ($val == "3s") {
      $display = "❸";
    }
    else if ($val == "4s") {
      $display = "❹";
    }
    else if ($val == "5s") {
      $display = "❺";
    }
    else if ($val == "6s") {
      $display = "❻";
    }
    else if ($val == "7s") {
      $display = "❼";
    }
    else if ($val == "8s") {
      $display = "❽";
    }
    
    else {
      $display = $val;
    }
    echo "<input type='text' value='$display' class='field field_hidden' readonly='readonly' style='width:$percent%; cursor: pointer' id='$index' $disabled>";
    $c++;
    if ($c == $size) {
      echo "<br>";
      $c = 0;
    }
  }
  echo "<input type='hidden' name='move' id='move'>";
  echo "<input type='hidden' name='id' value='$id'>";
  echo "<input type='hidden' name='diff' value='$diff'>";
  echo "<input type='submit' style='display:none' id='send'>";
  echo "</form>";
}

function is_game_over($id) {
  global $conn;
  $sql = "SELECT * from games WHERE game_id = '$id'";
  $result = mysqli_query($conn, $sql);
  $row = $result->fetch_row();
  return $row[2];
}
?>
