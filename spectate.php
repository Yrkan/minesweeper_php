<style>
input {
  text-align: center;
  font-size: 1.5em;
}
button {
  font-size: 1.5em;
  margin: 10px;
}

</style>

<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include "funcs.php";
session_start();

// bad request
if (!isset($_GET["diff"]) || !isset($_GET["move"])) {
  header("Location: index.php");
}


$id = $_GET["id"];
$diff = $_GET["diff"];
$move = intval($_GET["move"]);
$data = get_game_data($_GET["id"]);
$data = explode(",", $data);
array_pop($data);
$maxmoves = count($data) - 1;
$data = $data[$move];
$data = explode(".", $data);

switch ($diff) {
  case 'easy':
    draw_data($data, 8);
    break;

  case 'medium':
    draw_data($data, 14);
    break;

  case 'hard':
    draw_data($data, 20);
    break;
  default:
    # code...
    break;
}

?>

<form>
<?php
  echo "<input type='number' name='move' min='1' max=$maxmoves value=$move id='moveinput'>";
  echo "<input type='hidden' name='id' value='$id'>";
  echo "<input type='hidden' name='diff' value='$diff'>";
  echo "<button id='next'>next</button>";
  echo "<button id='prev'>previous</button>";
  echo "<input type='submit' style='display: none' id='send' value='send'>";
?>
<form>

<script>

document.getElementById("next").addEventListener("click", function(e) {
  let moveinput = document.getElementById("moveinput");
  if ( parseInt(moveinput.value)  + 1 <= parseInt(document.getElementById("moveinput").max)) {
    moveinput.value++;
  }
})

document.getElementById("prev").addEventListener("click", function(e) {
  let moveinput = document.getElementById("moveinput");
  if ( parseInt(moveinput.value) - 1 >= parseInt(document.getElementById("moveinput").min)) {
    moveinput.value--;
  }
})
</script>