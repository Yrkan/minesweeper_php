<style>
input {
  text-align: center;
  /*background: url(images/test.jpg) no-repeat scroll 7px 7px;
  background-position: center;*/
}

</style>

<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include "funcs.php";
session_start();

// bad request
if (!isset($_SESSION["id"]) || !isset($_GET["id"]) || !isset($_GET["diff"])) {
  header("Location: index.php");
}

// unauthorized access
if ($_SESSION["id"] != $_GET["id"]) {
  header("Location: index.php");
}

$id = $_GET["id"];
$diff = $_GET["diff"];
$data = "";
if (is_new_game($id)) {
  init_game($id, "", 1);
}
switch ($diff) {
  case 'easy':
    $data = gen_data(4);
    draw_data($data, 4);
    break;

  case 'medium':
    $data = gen_data(8);
    draw_data($data, 8);
    break;

  case 'hard':
    $data = gen_data(12);
    draw_data($data, 12);
    break;
  default:
    # code...
    break;
}

echo "<h2>Partager le jeux</h2>";
echo "<a href='http://localhost/spectate.php?id=$id&move=0'>spectate link</a>";
?>

<script>
var fields = document.querySelectorAll(".field")
fields.forEach(function(field) {
  field.addEventListener("click", function(e) {
    e.preventDefault()
    document.getElementById("move").value = e.target.id;
    document.getElementById("send").click();

  })
})
</script>