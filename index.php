<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
include "db.php";
include "funcs.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demineurs</title>
</head>

<body>
    <h1>Welcome</h1>
    <form action="game.php">
        <?php
            $id = gen_uuid();
            session_start();
            $_SESSION["id"] = $id;  
            echo "<input type='hidden' name='id' value=$id>"
        ?>
        <label for="easy">easy</label>
        <input type="radio" name="diff" id="easy" value="easy" selected>
        <label for="medium">medium</label>
        <input type="radio" name="diff" id="medium" value="medium">
        <label for="hard">hard</label>
        <input type="radio" name="diff" id="hard" value="hard">
        <input type="submit" value="start">
    </form>
</body>
</html>