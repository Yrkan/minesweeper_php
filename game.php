<style>
input {
  text-align: center;
  font-size: 1.5em;
}

</style>

<?php

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
    $data = gen_data(8);
    draw_data($data, 8);
    break;

  case 'medium':
    $data = gen_data(14);
    draw_data($data, 14);
    break;

  case 'hard':
    $data = gen_data(20);
    draw_data($data, 20);
    break;
  default:
    # code...
    break;
}

echo "<h2>Partager le jeux</h2>";
echo "<a href='http://localhost/projet/spectate.php?id=$id&move=1&diff=$diff'>spectate link</a>";
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