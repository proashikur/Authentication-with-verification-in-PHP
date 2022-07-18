<?php 
error_reporting(~E_NOTICE);
require_once 'dbconfig.php';

class USER
{
	private $conn;

	public function __construct()
	{
		$database=new Database();
		$db=$database->dbConnection();
		$this->conn=$db;
	}

	public function runQuery($sql)
	{
		$stmt=$this->conn->prepare($sql);
		return $stmt;
	}

	public function lastID()
	{
		$stmt=$this->conn->lastInsertId();
		return $stmt;
	}

	public function register($uname,$email,$upass,$code)
	{
		try
		{
			$password=md5($upass);
			$stmt=$this->conn->prepare("INSERT INTO tbl_users(userName,userEmail,userPass,tokenCode) VALUES(:user_name,:user_mail,:user_pass,:active_code)");

			$stmt->bindparam(":user_name",$uname);
			$stmt->bindparam(":user_mail",$email);
			$stmt->bindparam(":user_pass",$password);
			$stmt->bindparam(":active_code",$code);
			$stmt->execute();

			return $stmt;
		}

		catch(PDOException $ex)
		{	
			echo $ex->getMessage();
		}
	}

	public function login($email,$upass)
	{
		try
		{
			$stmt=$this->conn->prepare("SELECT * FROM tbl_users WHERE userEmail=:email_id");

			$stmt->execute(array(":email_id"=>$email));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);

			if($stmt->rowCount()==1)
			{
				if($userRow['userStatus']=='Y')
				{
					if($userRow['userPass']==md5($upass))
					{
						$_SESSION['userSession']=$userRow['userID'];
						return true;
					}

					else
					{
						header('location: index.php?error');
						exit;
					}

					

				}

				else
					{
						header('location: index.php?inactive');
						exit;
					}


			}
			else
				{
					header('location: index.php?error');
					exit;
				}
		}

		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	}

	public function is_logged_in()
	{
		if(isset($_SESSION['userSession']))
		{
			return true;
		}
	}

	public function redirect($url)
	{
		header("Location: $url");
	}


	public function logout()
	{
		session_destroy();
		$_SESSION['userSession']=false;

	}

	public function sendMail($email,$message,$subject)
	{
		require_once 'mailer/class.phpmailer.php';
		require_once 'mailer/class.smtp.php';

		$mail=new PHPMailer;

		//$mail->SMTPDebug=3;

		$mail->isSMTP();
		$mail->Host='smtp.gmail.com';
		$mail->SMTPAuth=true;
		$mail->Username='araman666@gmail.com';
		$mail->Password='';
		$mail->SMTPSecure='tls';
		$mail->Port=587;
		$mail->setFrom('araman666@gmail.com','US-Software');
		$mail->addAddress($email);
		$mail->addReplyTo('araman666@gmail.com','US-Software');
		$mail->isHTML(true);
		$mail->Subject=$subject;
		$mail->Body=$message;

		if(!$mail->send())
		{
			$_SESSION['MailError']=$mail->ErrorInfo;
			return false;
		}

		else
		{
			return true;
		}

	}




}





?>