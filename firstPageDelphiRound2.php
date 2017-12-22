<?php session_start();
$ppName = $ppSurname = "";
$ppNameErr = $ppSurnameErr = "";
$requiredFields = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $ppResp  = $surnameResp = FALSE; 
  if (empty($_POST['ppName'])) { $ppNameErr = "*"; } 
  else { $ppName = test_input($_POST["ppName"]); $ppResp = TRUE;}
  if (empty($_POST['ppSurname'])) { $ppSurnameErr = "*"; } 
  else { $ppSurname = test_input($_POST["ppSurname"]); $surnameResp = TRUE;}
  $ppID = makeID($ppName . $ppSurname);
  $_SESSION['ppid'] = $ppID;
  $_SESSION['name'] = $ppName;
  $_SESSION['surname'] = $ppSurname;
  if( $ppResp && $surnameResp) {
    require '../includeDatabase.php';
		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {	die("Connection failed: " . $conn->connect_error);	}
		$sql = "SELECT uniqueID FROM `{$tableName}` WHERE uniqueID = '{$ppID}'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			$sql = "INSERT IGNORE INTO mriTIN (`uniqueID`, `session`) VALUES ('{$ppID}', 2);";
			if ($conn->query($sql) === FALSE) { echo "Error: " . $sql . "<br>" . $conn->error; }
			$conn->close();
			header("Location: http://localhost/~mp/part2/delphi2_2.php");
			exit;
		} else {
			echo "<p style='background-color:#d73027; color:white; font-size:x-large;'>We could not find a user matching " . $ppName . " " . $ppSurname . "<br>";
			echo "If you did participate in Round one of this questionnaire then you might be using an ID we do not recognize. Please get in touch with Paolo (" ;
			echo "<a href='mailto:p.toffanin@umcg.nl'>p.toffanin@umcg.nl</a>) to sort out the issue. Apologies for the inconveniences.</p>";
		}
		$conn->close();
  } else {$requiredFields = "* required fields";} // END: if( $resp1 && $resp2 && $resp3 && $resp4 &&
} // END: if ($_SERVER["REQUEST_METHOD"] == "POST") {

function test_input($data) {$data = trim($data); $data = stripslashes($data); $data = htmlspecialchars($data); return $data; } 
function makeID($string){$string = test_input($string);$string = str_replace(' ', '', $string);$string = strtolower($string);return $string;}
?>

<!DOCTYPE html>
<html>
<head>
<title>Round II Delphi Tinnitus (f)MRI</title>
<meta name="keywords" content="Delphi method, tinnitus, fmri, round two, methods, validation, UMCG, Groningen">
<meta name="description" content="Web based delphi method study on fMRI and tinnitus, round II">
<meta name="author" content="Paolo Toffanin">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html;charset=utf-8;language=english">
<link rel="stylesheet" type="text/css" href="style2ndTable.css">
<script>
</script>
</head>
<body>
<img src="../tinnet_2.jpg" alt="TINNET logo" style="width:354px;height:181px;">

<h2>Delphi study on reporting of tinnitus (f)MRI studies - Round II</h2> 
<p> Welcome to the second round of the Delphi study on the reporting of MRI tinnitus studies. You are invited to complete this questionnaire no later than December 4th 2017 at 12.00 PM. </p>

<p>Please fill in your name and surname so that we can retrieve your previous responses.</p>

<p><span class="error"><?php echo $requiredFields;?></span></p>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

<p>Name <input type="text" name="ppName" value="<?php echo $ppName;?>"><span class="error"><?php echo $ppNameErr;?></span>
Surname <input type="text" name="ppSurname" value="<?php echo $ppSurname;?>"><span class="error"><?php echo $ppSurnameErr;?></span>
</p>

<p>
<input type="submit" name="submit" value="START">
<p>
  
</form>
</body>
</html>
