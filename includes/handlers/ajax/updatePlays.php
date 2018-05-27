<?php

// Used to update the number of Plays on a track

include("../../config.php");

if(isset($_POST['songId'])) {
  $songId = $_POST['songId'];

  $query = mysqli_query($con, "UPDATE songs SET plays = plays + 1 WHERE id='$songId'");
}


?>
