<!--
	    Name: <?php echo $name . "\n"; ?>
		Student Number: <?php echo $studentid . "\n"; ?>
		Course Code: <?php echo $coursecode . "\n"; ?>
		Date: <?php echo $date; ?>
		
	-->
<?php

$title = "Logout";
    include "./includes/header.php";
    session_unset();
    session_destroy();

    $today = date("Ymd");
    $now = date("Y-m-d G:i:s");
    $handle = fopen("./logs/".$today."_log.txt", 'a');

    fwrite($handle, "You Sucessfully logged out at ".$now.". User ".$email_address." sign in.\n");
    fclose($handle);


    header("Location:sign-in.php");

?>