<!--
	    Name: <?php echo $name . "\n"; ?>
		Student Number: <?php echo $studentid . "\n"; ?>
		Course Code: <?php echo $coursecode . "\n"; ?>
		Date: <?php echo $date; ?>
		
	-->
<?php
$title = 'Dashboard';
#require("./includes/constants.php");
function  db_connect()
{
    return pg_connect("host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DATABASE . " user=" . DB_ADMIN . " password=" . DB_PASSWORD);
}
$conn = db_connect();
$stmt2 = pg_prepare($conn, 'user_retrive', 'SELECT * FROM users WHERE emailAddress=$1');

function user_select($email_address)
{
    global $conn;
    $results = pg_execute($conn, 'user_retrive', array($email_address));

    if (pg_num_rows($results) == 1) {
        $user = pg_fetch_assoc($results, 0);
        return $user;
    } else {
        return false;
    }
}



$user_insert = pg_prepare($conn, "user_insert", "INSERT INTO users(emailAddress, password, firstName, lastName, enrollDate, Type)" . "
VALUES($1, $2, $3, $4, $5, 'a')");

function insert_user($email_address, $password1, $fName, $lName, $enrollDate)

{
    global $conn;
    return pg_execute($conn, "user_insert", array($email_address, password_hash($password1, PASSWORD_BCRYPT), $fName, $lName, $enrollDate));
}

$client_insert = pg_prepare($conn, "client_insert", "INSERT INTO clients(emailAddress, firstName, lastName, phoneNumber, phoneExtension, logoPath, sales_id)" . "VALUES ($1, $2, $3, $4, $5, $6, $7)");

function insert_client($email_address, $firstName, $lastName, $phoneNumber, $phoneExtension, $logoPath, $salesId)
{
    global $conn;
    return pg_execute($conn, "client_insert", array($email_address, $firstName, $lastName, $phoneNumber, $phoneExtension, $logoPath, $salesId));
}


//ABHI BANAYA HAI 

//$password_update = pg_prepare($conn);
$sql = "UPDATE users SET password =$1 WHERE emailAddress = $2";
$password_update = pg_prepare($conn, 'password_update', $sql);
function update_password($email_address, $new_password)
{
    global $sql;
    global $conn;
    $user = user_select($email_address);

    if ($user) {
        return pg_execute($conn, 'password_update', array(password_hash($new_password, PASSWORD_BCRYPT), $email_address));
    }
}

$user_select_all = pg_prepare($conn, "user_select_all", "SELECT id, emailAddress, firstName, lastName, enrollDate FROM users WHERE Type = $1");
function user_select_all($type)
{
    global $conn;
    return pg_execute($conn, 'user_select_all', array($type));
}

function agent_select_all($page)
{
    $result = user_select_all('a');
    $count = pg_num_rows($result);
    $arr = array();
    for ($i = ($page - 1) * RECORDS; $i < $count && $i < $page * RECORDS; $i++) {
        array_push($arr, pg_fetch_assoc($result, $i));
    }
    return $arr;
}

function agent_count()
{
    return pg_num_rows(user_select_all(AGENT));
}

$client_select_all = pg_prepare($conn, "client_select_all", "SELECT Id, emailAddress, firstName, lastName, phoneNumber, phoneExtension, logoPath FROM clients");


// $client_select_agent = pg_prepare($conn, "client_select_agent", "SELECT * FROM clients WHERE ");

function client_select_all($page)
{
    $arr = array();
    global $conn;
    if ($_SESSION['user']['type'] == 's') {
        $result = pg_execute($conn, "client_select_all", array());
        $count = pg_num_rows($result);

        for ($i = ($page - 1) * RECORDS; $i < $count && $i < $page * RECORDS; $i++) {
            array_push($arr, pg_fetch_assoc($result, $i));
        }
        return $arr;
    }
    else if ($_SESSION['user']['type'] == 'a')
    {
        $result = user_clients_select(getUser()['id']);
        $count = pg_num_rows($result);

        for ($i = ($page - 1) * RECORDS; $i < $count && $i < $page * RECORDS; $i++) {
            array_push($arr, pg_fetch_assoc($result, $i));
        }
        return $arr;
    }
}

// function client_select_agent($page)
// {
//     $arr = array();
//     global $conn;
//     if ($_SESSION['user']['type'] == 'a') {
//         $result = pg_execute($conn, "client_select_all", array());
//         $count = pg_num_rows($result);

//         for ($i = ($page - 1) * RECORDS; $i < $count && $i < $page * RECORDS; $i++) {
//             array_push($arr, pg_fetch_assoc($result, $i));
//         }
//     }
//     return $arr;
// }

function client_count()
{
    global $conn;
    if ($_SESSION['user']['type'] == 's') {
        $result = pg_execute($conn, "client_select_all", array());
        return pg_num_rows($result);
    }
    else if ($_SESSION['user']['type'] == 'a')
    {
        $result = pg_execute($conn, "client_select_all", array());
        return pg_num_rows($result);
    }
}

function user_authenticate($email_address, $password)
{
    $user = user_select($email_address);
    $_SESSION['user'] = $user;
    $_SESSION['message'] = "You successfully logged-in!! ";

    $message = "";
    $today = date("Y-m-d");
    $now = date("Y-m-d G:i:s");
    $handle = fopen("logs/" . $today . ".txt", 'a');
    if ($user && password_verify($password, $user['password'])) {
        fwrite($handle, "Sign in success at " . $now . ". User " . $email_address . "sign in.\n");

        header("location:dashboard.php");
        ob_flush();
    } else {
        $message = "User name or password is incorrect";
        fwrite($handle, "Sign in failed at " . $now . ". User " . $email_address . "sign in.\n");
    }

    fclose($handle);
}


$user_type_select = pg_prepare($conn, "user_type_select", " SELECT * FROM users WHERE Type = $1");

function user_type_select($type)
{
    global $conn;
    return pg_execute($conn, "user_type_select", array($type));
}

$user_clients_select = pg_prepare($conn, "user_clients_select", "SELECT * FROM clients WHERE sales_id = $1");

function user_clients_select($id)
{
    global $conn;

    return pg_execute($conn, "user_clients_select", array($id));
}

function setUser($user)
{
    $_SESSION['user'] = $user;
}
function getUser()
{
    return $_SESSION['user'];
}
function isUser()
{
    return isset($_SESSION['user']);
}
