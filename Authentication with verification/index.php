<?php 
session_start();

require_once 'class.user.php';

$user_login=new USER();

if($user_login->is_logged_in()!="")
{
	$user_login->redirect('home.php');
}

if(isset($_POST['btn-login']))
{
	$email=trim($_POST['txtemail']);
	$upass=trim($_POST['txtupass']);

	if($user_login->login($email,$upass))
	{
		$user_login->redirect('home.php');
	}

}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
</head>
<body>

	<div>
		
		<?php 

			if(isset($_GET['inactive']))
			{
				?>

				<div>
					<b>
						Sorry!</b> This account is not activated. Go To your Inbox and Activate it.
					
				</div>

				<?php
			}

		?>

		<form method="POST">

			<?php 
				if(isset($_GET['error']))
				{
				?>

					<div>
						<b>Wrong Details!</b>
					</div>

					<?php
				}
			?>

			<h2>Sign In</h2>
			<hr>
			<input type="email" name="txtemail" placeholder="Email Address" required><br>
			<input type="password" name="txtupass" placeholder="Password" required>
		<hr>
		<button type="submit" name="btn-login">Sign In</button>
		<a href="signup.php">Sign Up</a><hr>
		<a href="fpass.php">Lost Your Password?</a>			
		</form>
	</div>

</body>
</html>