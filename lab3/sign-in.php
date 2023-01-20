<!--
	    Name: <?php echo $name . "\n"; ?>
		Student Number: <?php echo $studentid . "\n"; ?>
		Course Code: <?php echo $coursecode . "\n"; ?>
		Date: <?php echo $date; ?>
		
	-->
<?php
$title = "Sign-in Page";
include "./includes/header.php";
$message = "";
$today = date("Y-m-d");
$now = date("Y-m-d G:i:s");

$handle = fopen("logs/" . $today . ".txt", 'a');

$user = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email_address = trim($_POST['inputEmail']); #validate email address
    $password = trim($_POST['inputPassword']);

    //This is normal sql statement
    // $sql = "SELECT * FROM users WHERE emailAddress = '" . $email_address . "' AND password = '" . $password . "'";

    //This is prepared sql statement


    //This is for normal sql statement
    // $results = pg_query($conn, $sql);

    //This is for prepared sql statement
    //$user = user_select($email_address);

    user_authenticate($email_address, $password);
}
?>

<form class="form-signin" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
    <h2><?php echo $message; ?></h2>
    <h1 class=" h3 mb-3 font-weight-normal">Please sign in</h1>
    <label for="inputEmail" class="sr-only">Email address</label>
    <input type="email" id="inputEmail" name="inputEmail" class="form-control" placeholder="Email address" required autofocus>
    <label for="inputPassword" class="sr-only">Password</label>
    <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" required>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
</form>

<?php
include "./includes/footer.php";
?>