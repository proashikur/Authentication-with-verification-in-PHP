<?php 
session_start();
require_once 'class.user.php';

$reg_user= new USER();
if(isset($_POST['btn-signup']))
{
	$uname=trim($_POST['txtuname']);
	$email=trim($_POST['txtemail']);
	$upass=trim($_POST['txtpass']);
	$code=md5(uniqid(rand()));

	$stmt=$reg_user->runQuery("SELECT * FROM tbl_users WHERE userEmail=:email_id");
	$stmt->execute(array(":email_id"=>$email));
	$row=$stmt->fetch(PDO::FETCH_ASSOC);

	if($stmt->rowCount()>0)
	{
		$msg="

				<div>
					<b>Sorry! Email allready taken, Please try another one.</b>
					</div>";
	}

	else
	{
		if($reg_user->register($uname,$email,$upass,$code))
		{
			$id=$reg_user->lastID();
			$key=base64_encode($id);//it stores encrypted ID
			$id=$key;

			$message=" Hello $uname, 
						<br><br>
						Welcome to my web portal!<br>
						To complete your registration, just click following link<br>
						<br><br>
						<a href='http://localhost/all_PHP_files/Authentication with verification/verify.php?id=$id&code=$code'>Click Here to Activate Your Account</a>
						<br><br>
						Thanks,";


						$subject="Confirm Registration";

						$reg_user->sendMail($email,$message,$subject);

						$msg="

							<div>

								<b>Registration done! We have sent an email to $email. Please click on confirmation link in the email to activate your account.
							</div>
						";

		}

		else
		{
			echo "Sorry, Query could not execute...";
		}
	}

}


?>



<!DOCTYPE html>
<html>
<head>
	<title>Signup Page</title>
</head>
<body>
	<div>
		
	<?php 

		if(isset($msg))
			echo $msg;
	?>

	<form method="POST">
		<h2>Sign Up Here..</h2>

		<input type="text" name="txtuname" placeholder="Username" required><br>
		<input type="email" name="txtemail" placeholder="Email Address" required><br>
		<input type="password" name="txtpass" placeholder="Password" required>
		<hr>
		<button type="submit" name="btn-signup">SignUp</button>
		<a href="index.php" style="float: left;">Sign In</a>
	</form>
</div>
</body>
</html>