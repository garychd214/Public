<?php session_start();?>
	<?php
$DID=$EID=$message="";
if(count($_POST)>0) {
	$conn = mysqli_connect("localhost","root","","autopart_project");
	$query = "SELECT * FROM employee WHERE EID='" . $_POST["EID"] . "' and ELNAME = '". $_POST["LastName"]."'";
	$result = mysqli_query($conn,$query);
	
	if(!$result || mysqli_num_rows($result) == 0) {
		$message = "Invalid Employee ID or Lastname!";
	} else {
		 while($row = mysqli_fetch_array($result))
		{
			$_SESSION['DID'] = $row['DID']; 
			$_SESSION['EID'] = $row['EID'];
		}
		header("location: Main.php");
	}
}
?>
<html>
<head>
<title>User Login</title>
<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
<form name="frmUser" method="post" action="">
	<div class="message"><?php if($message!="") { echo $message; } ?></div>
		<table border="0" cellpadding="10" cellspacing="1" width="500" align="center" class="tblLogin">
			<tr class="tableheader">
			<td align="center" colspan="2">Please Enter Employee ID and Last Name.</td>
			</tr>
			<tr class="tablerow">
			<td>
			<input type="text" name="LastName" placeholder="LastName" class="login-input"></td>
			</tr>
			<tr class="tablerow">
			<td>
			<input type="password" name="EID" placeholder="Employee ID" class="login-input"></td>
			</tr>
			<tr class="tableheader">
			<td align="center" colspan="2"><input type="submit" name="submit" value="Submit" class="btnSubmit"></td>
			</tr>
		</table>
</form>
</body></html>