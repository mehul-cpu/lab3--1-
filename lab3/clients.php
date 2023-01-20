<?php
include "./includes/header.php";
$title = "Clients Registration.";
?>

<?php

?>

<?php
$email_address = "";
$fName = "";
$lName = "";
$number = 0;
$extension = 0;
$salesId = 0;
$error = "";
$message = "";
$enrollDate = date("Y-m-d G:i:s");
$file = "";

if (!(isset($_SESSION['user']['type']) && ($_SESSION['user']['type'] == ADMIN) || ($_SESSION['user']['type'] == AGENT))) {
    header("Location:sign-in.php");
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $email_address = "";
    $fName = "";
    $lName = "";
    $number = 0;
    $extension  = 0;
    $logoPath = "";
    $error = "";
    $salesId = 0;
    $message = "";
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_address = trim($_POST['inputEmail']);
    $fName = trim($_POST['inputFName']);
    $lName = trim($_POST['inputLName']);
    $number = trim($_POST['inputNumber']);
    $extension = trim($_POST['inputExtension']);
    //$_POST['uploadFileName'];
    if (isset($_SESSION['user']['type']) && ($_SESSION['user']['type'] == ADMIN)) {
        $sales_person = $_POST['list'];
    }
    #$salesId = trim($_POST['inputSalesId']);
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
    if (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
        $error .= $email_address . "THIS EMAIL ADDRESS IS NOT VALID.</br>";
        $email_address = "";
    }
    if (user_select($email_address)) {
        $error .= "This email (" . $email_address . ") already exits.</br>";
    }
    if (!isset($number) || $number == "") {
        $error .= "You must enter a phone number.</br>";
        $number = 0;
    }
    /*if (isset($sales_person) && $sales_person = -1) {
        $error .= "You must select a sales person.</br>";
    }*/

    /*if ($_FILES['uploadFileName']['error'] != 0) {
        $error .= "Problem uploadFileing your file.</br>";
    } else if (
        $_FILES['uploadFileName']['type'] != "image/jpeg"
        && $_FILES['uploadFileName']['type'] != "image/pjpeg"
        && $_FILES['uploadFileName']['type'] != "image/gif"
        && $_FILES['uploadFileName']['type'] != "image/png"
    ) {
        $error .= "Your profile picture must be a JPEG image.</br>";
    } else if ($_FILES['uploadFileName']['size'] > 3000000) {
        $error .= "Your profile picture must smaller than 3MB.</br>";
    } else {
        $logoPath = "./files_uploadFileed/" . $email_address . "new_file.jpeg";
        move_uploadFileed_file($_FILES['uploadFileName']['tmp_name'], $logoPath);
    }*/

    if ($_FILES['uploadFile']['error'] != 0) {
        $error .= "Problem uploading the file. </br>";
    } else if (
        $_FILES['uploadFile']['type'] != "image/jpeg"
        && $_FILES['uploadFile']['type'] != "image/pjpeg"
        && $_FILES['uploadFile']['type'] != "image/gif"
        && $_FILES['uploadFile']['type'] != "image/png"
    ) {
        $error .= " Your uploaded file must be of type JPEG. </br>";
    } else if ($_FILES['uploadFile']['size'] > 3000000000) {
        $error .= "<br/> <em> The file selected is too big, your file must be smaller than 3MB. </em>";
    } else {
        $logoPath = "./files_uploaded/" . $email_address . "new_file.jpeg";
        move_uploaded_file($_FILES['uploadFile']['tmp_name'], $logoPath);
    }

    if ($error == "") {
        if (isset($_SESSION['user']['type']) && ($_SESSION['user']['type'] == AGENT)) {
            $salesId = $_SESSION['user']['id'];
        } else {
            $salesId = $sales_person;
        }
        $result = user_type_select(AGENT);
        $user = pg_fetch_assoc($result);
        //$salesId = $user["id"];
        insert_client($email_address, $fName, $lName, $number, $extension, $logoPath, $salesId);
        $_SESSION['message'] = "You were Successfully Registered. Welcome aboard!</br>";
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
$form_client = array(
    array(
        "type" => "text",
        "name" => "inputFName",
        "value" => $fName,
        "label" => "First Name",
    ),
    array(
        "type" => "text",
        "name" => "inputLName",
        "value" => $lName,
        "label" => "Last Name",
    ),
    array(
        "type" => "email",
        "name" => "inputEmail",
        "value" => $email_address,
        "label" => "Email Address",
    ),
    array(
        "type" => "integer",
        "name" => "inputNumber",
        "value" => $number,
        "label" => "Phone Number",
    ),
    array(
        "type" => "integer",
        "name" => "inputExtension",
        "value" => $extension,
        "label" => "Phone Extension",
    ),
    array(
        "type" => "file",
        "name" => "uploadFile",
        "value" => "",
        "label" => "Select File to uploadFile",
    ),
    array(
        "type" => "integer",
        "name" => "inputSalesId",
        "value" => "",
        "label" => "Sales ID",
    ),
    array(
        "type" => "select",
        "name" => "list",
        "value" => "",
        "label" => "List",
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
    )
);

display_Form($form_client);
$page = 1;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

if (($_SESSION['user']['type'] == ADMIN)) {
    display_table(
        array(
            "salesId" => "ID",
            "email_address" => "Email Address",
            "fName" => "First Name",
            "lName" => "Last Name",
            "inputNumber" => "Phone Number",
            "extension" => "Extension",
            "logoPath" => "Logo",
        ),
        client_select_all($page),
        client_count(),
        $page

    );
} else if (($_SESSION['user']['type'] == AGENT)) {
    display_table(
        array(
            "salesId" => "ID",
            "email_address" => "Email Address",
            "fName" => "First Name",
            "lName" => "Last Name",
            "inputNumber" => "Phone Number",
            "extension" => "Extension",
            "logoPath" => "Logo",
        ),
        client_select_all($page),
        client_count(),
        $page

    );
}


?>


<?php
include "./includes/footer.php";
?>