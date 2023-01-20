<!--
	    Name: <?php echo $name . "\n"; ?>
		Student Number: <?php echo $studentid . "\n"; ?>
		Course Code: <?php echo $coursecode . "\n"; ?>
		Date: <?php echo $date; ?>
		
	-->
<?php

include "./includes/header.php";
$title = "Sales Person Registration.";

$email_address = "";
$fName = "";
$lName = "";
$password1 = "";
$password2 = "";
$error = "";
$message = "";
$enrollDate = date("Y-m-d G:i:s");

if (!(isset($_SESSION['user']['type']) && ($_SESSION['user']['type'] == ADMIN))) {
    header("Location:sign-in.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_address = trim($_POST['inputEmail']);
    $fName = trim($_POST['inputFName']);
    $lName = trim($_POST['inputLName']);
    $password1 = trim($_POST['inputPassword1']);
    $password1 = trim($_POST['inputPassword2']);

    // Data Validation
    // First Name
    if (!isset($fName) || $fName == "") {
        $error .= "YOU MUST ENTER YOUR FIRST NAME.</br>";
    }
    // Last Name
    if (!isset($lName) || $lName == "") {
        $error .= "YOU MUST ENTER YOUR LAST NAME.</br>";
    }
    // Email Validation
    if (!isset($email_address) || $email_address == "") {
        $error .= "YOU MUST ENTER YOUR EMAIL ADDRESS.</br>";
    } 
    elseif (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
        $error .= $email_address . "THIS EMAIL ADDRESS IS NOT VALID.</br>";
        $email_address = "";
    } 
    elseif (user_select($email_address)) {
        $error .= "This email (" . $email_address . ") is already exits.</br>";
    }
    if ($error == "") {
        insert_user($email_address, $password1, $fName, $lName, $enrollDate);
        $_SESSION['message'] = "You were Successfully Registered. Welcome aboard!.</br>";

    } else {
        $error .= "Please Try Again.</br>";
        $_SESSION['message'] = $error;
    }
}
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

?>

<h3>
    <?php
    echo $message;
    ?>
</h3>

<?php
$form_user = array(
    array(
        "type" => "text",
        "name" => "inputFName",
        "value" => "",
        "label" => "First Name",
    ),
    array(
        "type" => "text",
        "name" => "inputLName",
        "value" => "",
        "label" => "Last Name",
    ),
    array(
        "type" => "email",
        "name" => "inputEmail",
        "value" => "",
        "label" => "Email Address",
    ),
    array(
        "type" => "password",
        "name" => "inputPassword1",
        "value" => "",
        "label" => "Password",
    ),
    array(
        "type" => "password",
        "name" => "inputPassword2",
        "value" => "",
        "label" => "Confirm Password",
    ),
    array(
        "type" => "submit",
        "name" => "",
        "value" => "",
        "label" => "Register",
    ),
    array(
        "type" => "reset",
        "name" => "",
        "value" => "",
        "label" => "Reset",
    ),
);
display_Form($form_user);
?>



<?php
include "./includes/footer.php";
?>