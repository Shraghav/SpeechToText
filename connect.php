<?php
$user = filter_input(INPUT_POST, 'user');
$user = html_entity_decode($user, ENT_COMPAT, "UTF-8");
$pass = filter_input(INPUT_POST, 'pass');
$pass = html_entity_decode($pass, ENT_COMPAT, "UTF-8");

if (!empty($user)){
if (!empty($pass)){
$host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "userform";
// Create connection
$conn = new mysqli ($host, $dbusername, $dbpassword, $dbname);


if (mysqli_connect_error()){
die('Connect Error ('. mysqli_connect_errno() .') '
. mysqli_connect_error());
}
else{
$sql = "INSERT INTO studentinfo (value1, value2)
values ('$user','$pass')";
if ($conn->query($sql)){
echo "<script type='text/javascript'>
       alert('Data Sent');
    </script>";
}
else{
echo "Error: ". $sql ."
". $conn->error;
}
}
}
else{
echo "Password should not be empty";
die();
}
}
else{
echo "Username should not be empty";
die();
}
$conn->close();

?>