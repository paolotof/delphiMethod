<?php session_start(); 
$obj = json_decode($_REQUEST['indexQ']);
$expertType = $obj->expType;
$obj = 'q' . $obj->qNum ;
require '../includeDatabase.php';
$conn = mysqli_connect($servername,$username,$password,$dbname);
if (mysqli_connect_errno()) { die('Could not connect: ' . mysqli_connect_error()); }
$sql = "SELECT `{$obj}`, COUNT(`{$obj}`) AS counts FROM `mriTIN` WHERE session = 1 AND  `id` NOT IN ('1', '2', '3', '7', '10') AND  `uniqueID` IN ( SELECT `uniqueID` FROM `ppPim` WHERE `rType` ={$expertType}) GROUP BY `{$obj}`;"; 
$result = mysqli_query($conn,$sql);
$response = array();
while($row = mysqli_fetch_row($result)) { $json['response'] = $row[0]; $json['counts'] = $row[1];array_push($response, $json); }
$sql = "SELECT uniqueID FROM `ppPim` WHERE uniqueID='{$_SESSION['ppid']}' AND rType={$expertType};" ;
$result = mysqli_query($conn,$sql);
if(mysqli_num_rows($result) > 0){
$sql = "SELECT `{$obj}`, COUNT(`{$obj}`) AS counts FROM `mriTIN` WHERE session = 1 AND uniqueID = '{$_SESSION['ppid']}';"; 
$result = mysqli_query($conn,$sql);
while($row = mysqli_fetch_row($result)){ $json['response'] = $row[0];$json['counts'] = $row[1];array_push($response, $json);} }
mysqli_close($conn);
echo json_encode($response);
?>
