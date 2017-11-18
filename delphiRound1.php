<?php session_start();
$questions = array(
	"Method of participant recruitment", 
	"Informed consent statement", 
	"Age", 
	"Gender", 
	"Handedness", 
	"Ethnicity", 
	"Education work and/or socio economic status",
	"Quality of life", 
	"Anxiety", 
	"Depression", 
	"Sleep quality", 
	"Ongoing treatment for tinnitus", 
	"Aetiology and onset of hearing loss (if hearing impaired)", 
	"ENT examination outcome", 
	"Standard tone audiogram (250 Hz – 8 kHz)", 
	"High frequency tone audiogram (>8 kHz)", 
	"Loudness discomfort level", 
	"Hyperacusis (e.g. a questionnaire score)", 
	"Speech audiometry", 
	"Aetiology and onset of tinnitus", 
	"Duration or onset age", 
	"Distinction objective/subjective tinnitus", 
	"Tinnitus handicap (e.g. a questionnaire score)", 
	"Type of percept (e.g. tonal, broadband, etc.)", 
	"Intermittence", 
	"Laterality", 
	"Tinnitus pitch (e.g. matched frequency or tinnitus spectrum)", 
	"Tinnitus loudness (e.g. matched level)", 
	"Minimum masking level", 
	"Residual inhibition", 
	"Hearing protection and sound attenuation", 
	"Headphones and sound delivery", 
	"A measure of the frequency content of scanner noise (e.g. spectrum or dominant frequency)", 
	"A measure of the sound level produced by the scanner", 
	"Use of inter-scan silent periods (e.g. sparse/clustered volume)", 
	"Repetition time (TR)", 
	"Image acquisition time (TA)", 
	"Audibility of tinnitus during scan session", 
	"Changes in tinnitus percept during scan session (e.g. loudness changes)", 
	"Changes in tinnitus immediately following scan session (e.g. loudness changes)");
$nQuestions = count($questions);
# initialize variables for empty responses
$q = $qErr = array_fill(0, $nQuestions, "");
$researcherType = $email = $ppName = $ppSurname = "";
$commentQuestionnaire = $commentMRI = "";
# set variables for 'required' option, will be 'filled' if value is left empty
$researcherTypeErr = $emailErr = $ppNameErr = $ppSurnameErr = "";
$requiredFields = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
//SANITY CHECKS: set variables determining if data should be posted if responses are valid
  $researcherResp = FALSE;
  if (empty($_POST['researcherType'])) { $researcherTypeErr = "*"; } 
  else { $researcherType = test_input($_POST["researcherType"]); $researcherResp = TRUE;}

  if (empty($_POST['email'])) { $emailErr = "*"; } 
  else { 
		$email = test_input($_POST["email"]); 
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		  $emailErr = "Invalid email format";
		} else { $emailErr = ""; $emailResp = TRUE;}}
  
  $ppResp = FALSE;
  if (empty($_POST['ppName'])) { $ppNameErr = "*"; } 
  else { $ppName = test_input($_POST["ppName"]); $ppResp = TRUE;}
  
	$surnameResp = FALSE;
  if (empty($_POST['ppSurname'])) { $ppSurnameErr = "*"; } 
  else { $ppSurname = test_input($_POST["ppSurname"]); $surnameResp = TRUE;}
  
  # create unique id for respondent and save on current session
  $ppID = makeID($ppName . $ppSurname);
  $_SESSION['ppid'] = $ppID;
  $_SESSION['name'] = $ppName;
  $_SESSION['surname'] = $ppSurname;
  
	$postComment = FALSE;
	if (!empty($_POST["commentQuestionnaire"])) {
	 $commentQuestionnaire = test_input($_POST["commentQuestionnaire"]);
	 $postComment = TRUE;
	}
	if (!empty($_POST["commentMRI"])) {
	 $commentMRI = test_input($_POST["commentMRI"]);
	 $postComment = TRUE;
	}
  
  $resp = array_fill(0, $nQuestions, FALSE);
  for ($iQuest = 0; $iQuest < $nQuestions; $iQuest++){
		if (empty($_POST['q' . ($iQuest+1)])) { $qErr[$iQuest] = "*"; } else { $q[$iQuest] = test_input($_POST['q' . ($iQuest+1)]); $resp[$iQuest] = TRUE;}
  }
  
  $postedAll = FALSE; 
  if( $ppResp && $surnameResp 
		&& $resp[1] && $resp[2] && $resp[3] && $resp[4] && $resp[5] && $resp[6] && $resp[7] && $resp[8] && $resp[9] && $resp[10]
		&& $resp[11] && $resp[12] && $resp[13] && $resp[14] && $resp[15] && $resp[16] && $resp[17] && $resp[18] && $resp[19] && $resp[20]
		&& $resp[21] && $resp[22] && $resp[23] && $resp[24] && $resp[25] && $resp[26] && $resp[27] && $resp[28] && $resp[29] && $resp[30]
		&& $resp[31] && $resp[32] && $resp[33] && $resp[34] && $resp[35] && $resp[36] && $resp[37] && $resp[38] && $resp[39] && $resp[40]) {
    require 'includeDatabase.php';
    $conn = mysqli_connect($servername, $username, $password, $dbname);
		if (!$conn) { die("Connection failed: " . mysqli_connect_error()); } else {echo "Connected";}
	  $sql = "INSERT INTO `{$tableName}` (`uniqueID`, `email`, `rType`, `session`,
					  `q1`, `q2`, `q3`, `q4`, `q5`, `q6`, `q7`, `q8`, `q9`, 
					  `q10`, `q11`, `q12`, `q13`, `q14`, `q15`, `q16`, `q17`, `q18`, `q19`, 
					  `q20`, `q21`, `q22`, `q23`, `q24`, `q25`, `q26`, `q27`, `q28`, `q29`, 
					  `q30`, `q31`, `q32`, `q33`, `q34`, `q35`, `q36`, `q37`, `q38`, `q39`, 
						`q40`) VALUES ('{$ppID}', '{$email}', '{$researcherType}',  '1', 
						'{$q1}', '{$q2}', '{$q3}', '{$q4}', '{$q5}', '{$q6}', '{$q7}', '{$q8}', '{$q9}', '{$q10}', 
						'{$q11}', '{$q12}', '{$q13}', '{$q14}', '{$q15}', '{$q16}', '{$q17}', '{$q18}', '{$q19}', '{$q20}', 
						'{$q21}', '{$q22}', '{$q23}', '{$q24}', '{$q25}', '{$q26}', '{$q27}', '{$q28}', '{$q29}', '{$q30}', 
						'{$q31}', '{$q32}', '{$q33}', '{$q34}', '{$q35}', '{$q36}', '{$q37}', '{$q38}', '{$q39}', '{$q40}');";
	  if (!mysqli_query($conn, $sql)){ die('Error: ' . mysqli_error($conn)); }
		if ($postComment){
			$mergedComment = $commentQuestionnaire . " " . $commentMRI;
			$mergedComment = mysqli_real_escape_string($conn, $mergedComment);
			$sql = "INSERT INTO userComments (`commentList`) VALUES ('{$mergedComment}');";
		  if (!mysqli_query($conn, $sql)){ die('Error comments: ' . mysqli_error($conn)); }
		} // END: if ($postComment){
    mysqli_close($conn);
    $postedAll = TRUE; 
  } else { $requiredFields = "* required fields"; }// END: if( $resp1 && $resp2 && $resp3 && ...
  if ($postedAll){
		header("Location: http://localhost/~mp/test1/pimQuestionnaire/thankYou.php");
		exit; }
} // END: if ($_SERVER["REQUEST_METHOD"] == "POST") {

function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
} // END: function test_input($data) {

function makeID($string){
	$string = test_input($string);
	$string = str_replace(' ', '', $string);
	$string = strtolower($string);
	return $string;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset=utf-8 />
<title>Delphi Method Tinnitus (f)MRI Round I</title>
<link rel="stylesheet" type="text/css" href="styleTable.css">
<script>
</script>
</head>
<body>


<h2>Delphi study on reporting of tinnitus (f)MRI studies - Round I</h2> 

<p> Before starting with the questionnaire please fill in these four general questions to identify you and your research background.</p>

<span class="error"><?php echo $requiredFields;?></span>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<p>
<input type="text" name="ppName" value="<?php echo $ppName;?>"><span class="error"><?php echo $ppNameErr;?></span>
First name
</p><p>
<input type="text" name="ppSurname" value="<?php echo $ppSurname;?>"><span class="error"><?php echo $ppSurnameErr;?></span> Last/family name
</p><p>
<input type="text" name="email" value="<?php echo $email;?>"><span class="error"><?php echo $emailErr;?></span>
Your preferred email address
</p>
<p> 
Which of the following best describes your involvement in tinnitus research? Please chose one of the options below<span class="error"><?php echo $researcherTypeErr;?></span>:
</p>
<p>
<input type="radio" name="researcherType" <?php if (isset($researcherType) && $researcherType=="1") echo "checked";?>  value="1">
Tinnitus researcher who has published MRI/fMRI tinnitus papers in the last 5 years, either structural of functional or both, but excluding MR spectroscopy.
</p>
<p>
<input type="radio" name="researcherType" <?php if (isset($researcherType) && $researcherType=="2") echo "checked";?>  value="2">
Tinnitus researcher who has published in the last 5 years on neuroscientific studies on humans with tinnitus, using MEG, EEG, ABR, or psychoacoustic techniques.
</p>
<p>
<input type="radio" name="researcherType" <?php if (isset($researcherType) && $researcherType=="3") echo "checked";?>  value="3">
None of the above. Sorry, I cannot participate.
</p>

<p>
How relevant do you consider the following items in a scientific report of an anatomical or structural MRI tinnitus patient? Note that we do not ask to specify which measures is to be used, rather whether a measure should be provided to report on the item. It is important that you only give a score 7-8-9 if you feel that the item is critically important in order to interpret the results that are being presented in the report.
</p>

<?php 

$headers = array("Participants Characteristics - Demographics",
"Participants Characteristics - General health",
"Participants Characteristics - Hearing",
"Participants Characteristics - Tinnitus",
"Technical characteristics of scan session - sound",
"Technical characteristics of scan session - Sequence parameters",
"Technical characteristics - Effect of scan session on tinnitus");
$iheader = 0;
$itemsPerHeader = array(0, 7, 12, 19, 30, 34, 37, 40);
for ($iQuest = 0; $iQuest < $nQuestions; $iQuest++) {
	if ($iQuest == $itemsPerHeader[$iheader]){
		echo "<h3>$headers[$iheader]</h3><table>";
		include("tableHeader.php");
		echo "<tbody>";
		$iheader++;
	}
	echo "<tr> <td class='firstRow'> $questions[$iQuest] </td>";
	for ($item = 1; $item <= 9; $item++) {
		echo "<td> <center> <input type='radio' name='q" . ($iQuest+1) ."'"; 
		if (isset($q[$iQuest]) && $q[$iQuest]==$item) echo "checked='' "; 
		echo "value='" . $item . "'> </center> </td>";
	}
	echo "<td class='unscored'> <center> <input type='radio' name='q" . ($iQuest+1) ."' ";
	if (isset($q[$iQuest]) && $q[$iQuest]=="10") echo "checked='' "; 
	echo " value='10'> </center> </td>";
	echo "<td class='asterisk'> <center> <span class='error'>" . $qErr[$iQuest] 
	     . "</span></center></td></tr>";
	// close table for last item
	if ($iQuest == ($itemsPerHeader[$iheader] - 1)){
		echo "</tbody> </table>";
	}
}
?>

<p>
Do you have any feedback on this questionnaire in general or on any specific items in the questionnaire?
</p>

<p>
Characters available: <input disabled  maxlength="3" size="3" value="1000" id="counterQ"><br>
<textarea name="commentQuestionnaire" rows="10" cols="50" onkeyup="textCounter(this,'counterQ',1000);" id="messageQ"><?php echo $commentQuestionnaire;?></textarea>
</p>

<p>
Are there items you consider critical in a report of an MRI tinnitus study, but are missing in this questionnaire?
</p>
<p>
Characters available: <input disabled  maxlength="3" size="3" value="1000" id="counterMRI"><br>
<textarea name="commentMRI" rows="10" cols="50" onkeyup="textCounter(this,'counterMRI',1000);" id="messageMRI"><?php echo $commentMRI;?></textarea>
</p>

<p>
If you have completed all the questions please submit your responses. All fields require a response, a part from filling in the comments. If, while filling in the questionnaire, some items are missed a red asterisk<span class="error">*</span> will appeared close to the missed item(s). Please check that all the fields have been filled in before submitting.
</p>
<p><input type='submit' name='submit' value='Submit'></p>

</form>

<script>
function textCounter(field,field2,maxlimit)
{
 var countfield = document.getElementById(field2);
 if ( field.value.length > maxlimit ) {
  field.value = field.value.substring( 0, maxlimit );
  return false;
 } else {
  countfield.value = maxlimit - field.value.length;
 }
}
</script>
</body>
</html>
