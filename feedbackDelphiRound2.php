<?php session_start();
if (!isset($_SESSION['count'])) $_SESSION['count'] = 0;

$questions = array(
	array(1, "Method of participant recruitment"), 
	array(2, "Informed consent statement"), 
	array(3, "Age"), 
	array(4, "Gender"), 
	array(5, "Handedness"), 
	array(6, "Ethnicity"), 
	array(7, "Education work and/or socio economic status"), 
	array(8, "Quality of life"),
	array(9, "Anxiety"), 
	array(10, "Depression"), 
	array(11, "Sleep quality"), 
	array(41, "Cognitive abilities"),
	array(12, "Ongoing treatment for tinnitus"), 
	array(42, "Current medication use"), 
	array(13, "Aetiology and onset of hearing loss (if hearing impaired)"), 
	array(14, "Outcome of otological examination (e.g. otoscopy)"), 
	array(15, "Standard tone audiogram (250 Hz – 8 kHz)"), 
	array(16, "High frequency tone audiogram (>8 kHz)"), 
	array(17, "Loudness discomfort level"), 
	array(18, "Hyperacusis (e.g. a questionnaire score)"), 
	array(19, "Speech audiometry"), 
	array(20, "Aetiology and onset of tinnitus"), 
	array(21, "Duration or onset age"), 
	array(22, "Distinction objective/subjective tinnitus"), 
	array(23, "Tinnitus handicap (e.g. a questionnaire score)"), 
	array(24, "Type of percept (e.g. tonal, broadband, etc.)"), 
	array(25, "Intermittence"), 
	array(26, "Laterality"), 
	array(27, "Tinnitus pitch (e.g. matched frequency or tinnitus spectrum)"), 
	array(28, "Tinnitus loudness (e.g. matched level)"), 
	array(29, "Minimum masking level"), 
	array(30, "Residual inhibition"), 
	array(31, "Hearing protection and sound attenuation"), 
	array(32, "Headphones and sound delivery"), 
	array(33, "A measure of the frequency content of scanner noise (e.g. spectrum or dominant frequency)"), 
	array(34, "A measure of the sound level produced by the scanner"), 
	array(35, "Use of inter-scan silent periods (e.g. sparse/clustered volume)"), 
	array(36, "Repetition time (TR)"), 
	array(37, "Image acquisition time (TA)"), 
	array(38, "Audibility of tinnitus during scan session"), 
	array(43, "Audibitity of the sound stimuli (if any) during the scan session"),
	array(39, "Changes in tinnitus percept during scan session (e.g. loudness changes)"), 
	array(40, "Changes in tinnitus immediately following scan session (e.g. loudness changes)"),
	array(44, "Attentional state during scan session (e.g. resting state questionnaire)"), 
	array(45, "Annoyance of acoustic environment of the scanner")
	);
$nQuestions = count($questions);
$counter = $_SESSION['count'];
require '../includeDatabase.php';
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) { die("Connection failed: " . mysqli_connect_error()); }
$q1 = $q1Err = $requiredFields = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$resp1 = FALSE;
	if (empty($_POST['q1'])){$q1Err="*";} else {$q1=test_input($_POST['q1']); $resp1=TRUE;}
	if ($resp1){
		$qNn = 'q' . ($questions[$counter][0]);
		$sql = "UPDATE `{$tableName}` SET `{$qNn}` = '{$q1}' 
			WHERE uniqueID =  '{$_SESSION['ppid']}' AND session = 2";
		if (!mysqli_query($conn, $sql)){ die('Error: ' . mysqli_error($conn)); }
		$q1 = $q1Err =  "";
		if (!isset($_SESSION['count'])) {$_SESSION['count'] = 0;} else {$_SESSION['count']++;}
	} else { $requiredFields = "* required fields"; }	// END: if( $resp1
} // END: if ($_SERVER["REQUEST_METHOD"] == "POST") {	
if ($_SESSION['count'] >= $nQuestions) {
	header("Location: http://paolo.mp-concepts.net/fmrtin/thankYou.php");
	exit;
}
$counter = $_SESSION['count'];
mysqli_close($conn);
function test_input($data){
	$data=trim($data);
	$data=stripslashes($data);
	$data=htmlspecialchars($data);
	return $data;}
function makeID($string){
	$string=test_input($string);
	$string=str_replace(' ', '', $string);
	$string=strtolower($string);
	return $string;}
?>
<!DOCTYPE html>
<head>
<title>Round 2 Delphi Tinnitus (f)MRI</title>
<meta name="keywords" content="Delphi method, tinnitus, fmri, questionnaire, methods, 
validation, round two">
<meta name="description" content="Web based delphi method study on fMRI and tinnitus">
<meta name="author" content="Paolo Toffanin">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html;charset=utf-8;language=english">
<link rel="stylesheet" type="text/css" href="style2ndTable.css">
</head>
<body>
<img src="../tinnet_2.jpg" alt="TINNET logo" style="width:354px;height:181px;">
<h2>Delphi study on reporting of tinnitus (f)MRI studies - Round II</h2> 

<p>This page displays the questionnaire items side by side with the graphs illustrating the results for each of the questions in the questionnaire you and the other experts filled 
in the previous round. The results in the bar graphs are coded as follow: the colors green, yellow and red reflects the importance of the item, 
"<span style="background-color:#1a9850; color:white;">Not important</span>", 
"<span style="background-color:#ffd700; color:black;">Important but not critical</span>", 
"<span style="background-color:#d73027; color:white;">Critical</span>", respectively. 
"Unable to score" is <span style="background-color:#f1b6da; color:black;">pink</span>. One
bar is <span style="background-color:#386cb0; color:white;">blue</span>, this is the bar 
representing your response to the given question. 
</p>
<p>
<span class="error"><?php echo $requiredFields;?></span>
</p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<div id="wrapper">
	<div id="first">
		<?php echo "<table>";
			include("tableHeader2nd.php"); 
			echo "<tbody><tr><td id='question' class='firstRow'>";
			echo $questions[$counter][1];
			echo ". </td>";
			for ($item = 1; $item <= 9; $item++) {
				echo "<td> <center> <input type='radio' name='q1'"; 
				if (isset($q1) && $q1=="1") echo "checked";
				echo " value='" . $item . "'> </center> </td>";}
			echo "<td class='unscored'> <center> <input type='radio' name='q1' ";
			if (isset($q1) && $q1=='10') echo 'checked';
			echo "value='10'> </center> </td>	<td class='asterisk'> <span class='error'>"; 
			echo $q1Err;
			echo "	</span> </td></tr></tbody></table>"; ?>
	</div>
	<div id="second">
		<p id="firstPlot" class="titlePlot"></p>
		<svg width="300" height="150"></svg> 
	</div>
	<div id="third">
		<p id="secondPlot" class="titlePlot"></p>
		<svg width="300" height="150"></svg> 
	</div>
</div>  <!-- //END: <div id="wrapper"> -->
<p>
<input type="submit" name="submit" value="Submit">
</p>
</form>

<script src="https://d3js.org/d3.v4.min.js"></script>
<script src="barplot.js"></script>
<script>
if (<?php echo ($questions[$counter][0]) ?> < 41){
document.getElementById('firstPlot').innerHTML = 'MRI experts';
document.getElementById('secondPlot').innerHTML = 'EEG, MEG, psychoacoustic experts';
var xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
		var myArr = JSON.parse(this.responseText);
		d3.select("#second").datum(myArr).call(barPlot());
	}};
var selResps = {qNum:"<?php echo ($questions[$counter][0]) ?>", expType: 1};
xmlhttp.open("GET", "fetchResults.php?indexQ=" + JSON.stringify(selResps), true);
xmlhttp.send();   
var xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
		var myArr = JSON.parse(this.responseText);
		d3.select("#third").datum(myArr).call(barPlot());
	}};
var selResps = {qNum:"<?php echo ($questions[$counter][0]) ?>", expType: 2};
xmlhttp.open("GET", "fetchResults.php?indexQ=" + JSON.stringify(selResps), true);
xmlhttp.send(); }
</script>
</body>
</html>
