<?php include "./includes/header.php";
$title = "Change Password" ?>

<?php
$email_address = "";
$new_password = "";
$confirm_new_password = "";
$error = "";
$message = "";

if (!(isset($_SESSION['user']['type']) && ($_SESSION['user']['type'] == ADMIN) || ($_SESSION['user']['type'] == AGENT))) {
    header("Location:sign-in.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = trim($_POST['newPassword']);
    $confirm_new_password = trim($_POST['confirmPassword']);
    $email_address = trim($_POST['emailAddress']);

    if (!isset($new_password) || $new_password == "") {
        $error .= "YOU MUST ENTER A PASSWORD.</br>";
    }
    if (!isset($confirm_new_password) || $confirm_new_password == "") {
        $error .= "YOU MUST ENTER A PASSWORD.</br>";
    }
    if ($new_password != $confirm_new_password) {
        $error .= "NEW AND CONFIRM PASSWORD HAVE DIFFERENT PASSWORDS.</br>";
    }
    if ($error != "") {
        $error .= "Please try again.</br>";
        $_SESSION['message'] = $error;
    }
    else{
        update_password($email_address,$confirm_new_password);
        $_SESSION['message'] = "Password changed successfully</br>";
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
$password_change = array(
    array(
        "type" => "email",
        "name" => "emailAddress",
        "value" => "",
        "label" => "Email Address",
    ),
    array(
        "type" => "password",
        "name" => "newPassword",
        "value" => "",
        "label" => "New Password",
    ),
    array(
        "type" => "password",
        "name" => "confirmPassword",
        "value" => "",
        "label" => "Confirm Password",
    ),
    array(
        "type" => "submit",
        "name" => "",
        "value" => "",
        "label" => "Change Password",
    )
);

display_Form($password_change);
?>

<?php include "./includes/footer.php"; ?>