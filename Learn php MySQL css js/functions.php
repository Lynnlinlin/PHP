<?php 
$dbhost = 'localhost';
$dbname = 'anexistingdb';
$dbuser = 'root';
$dbpass = '';
$appname = "Robin's Nest";

$dbserver = mysqli_connect($dbhost, $dbuser, $dbpass) or die(mysqli_error($dbserver));
mysqli_select_db($dbserver, $dbname) or die(mysqli_error());

function createTable($name, $query)
{
    queryMysql("CREATE TABLE IF NOT EXISTS $name($query)");
    echo "Table '$name' created or already exists.<br />";
}

function queryMysql($query)
{
    global $dbserver;
    $result = mysqli_query($dbserver, $query) or die(mysqli_error($dbserver));
    return $result;
}

function destroySession()
{
    $_SESSION = array();
    
    if(session_id() != "" || isset($_COOKIE[session_name()]))
        setcookie(session_name(), '', time()-2592000, '/');
    
    session_destroy();
}

function sanitizeString($var)
{
    global $dbserver;
    $var = strip_tags($var);
    $var = htmlentities($var);
    $var = stripslashes($var);
    return mysqli_real_escape_string($dbserver, $var);
}

function showProfile($user)
{
    global $dbserver;
    
    if(file_exists("$user.jpg"))
        echo "<img src='$user.jpg' align='left' />";
    
    $result = queryMysql("SELECT * FROM profiles WHERE user='$user'");
    
    if(mysqli_num_rows($result))
    {
        $row = mysqli_fetch_row($result);
        echo stripslashes($row[1]."<br clear=left /><br />");
    }
}
?>