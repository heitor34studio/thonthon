<?php
session_start();
require_once('db.class.php');
if(isset($_POST['valueUser']) || isset($_POST['valueEmail']) || isset($_POST['valuePass']) || isset($_POST['valuePass2'])){
		$usuario = addslashes($_POST['valueUser']);
		$email = addslashes($_POST['valueEmail']);
		$senha =  addslashes($_POST['valuePass']);
		$senha2 = addslashes($_POST['valuePass2']);
}else{
	$_SESSION['jooj'] = 'Erro ao enviar email de Ativação, está faltando algo.';
	echo '<script>window.location.href="cadastrar"</script>';
	die();
}
		$objDB = new db();
		$link = $objDB->conecta_mysql();
		$usuario_existe = false;
		$email_existe = false;
		$senhasDiferentes = false;
		$pass_size = strlen($senha);
		$email_size = strlen($email);
		$jooj = strlen($usuario);
		$jooj_1 = strlen($senha);
		$sql = "select * from usuarios where usuario = '$usuario' ";
		if($resultado_id = mysqli_query($link, $sql)){
			$dados_usuario = mysqli_fetch_array($resultado_id);
			if(isset($dados_usuario['usuario'])){
				$usuario_existe = true;
			}
		}else{
			$_SESSION['jooj'] = 'Erro ao tentar executar consulta. Contate o Dev.';
			echo '<script>window.location.href="cadastrar"</script>';
			die();
		}
		$sql = "select * from usuarios where email = '$email' ";
		if($resultado_id = mysqli_query($link, $sql)){
			$dados_usuario = mysqli_fetch_array($resultado_id);
			if(isset($dados_usuario['email'])){
				$email_existe = true;
			}
		}else{
			$_SESSION['jooj'] = 'Erro ao tentar executar consulta. Contate o Dev.';
			echo '<script>window.location.href="cadastrar"</script>';
			die();
		}
		if($senha != $senha2){
			$senhasDiferentes = true;
		}
		if($usuario_existe || $email_existe || $jooj_1 < 8 || $jooj < 4 || $senhasDiferentes || $jooj > 20 || $email_size > 50 || $pass_size > 60 ){
			$_SESSION['jooj'] = 'Algo deu errado, tente novamente.';
			echo '<script>window.location.href="cadastrar"</script>';
			die();
		}
		
		$senhaCrypt = password_hash($senha, PASSWORD_BCRYPT);
		$sql = " insert into usuarios(usuario, email, senha) values ('$usuario', '$email', '$senhaCrypt') ";
		if(mysqli_query($link, $sql)){
			$sql = " SELECT * FROM `usuarios` WHERE usuario = '$usuario' ";
			$ok = mysqli_query($link,$sql);
			if($ok){
				$ok1 = mysqli_fetch_array($ok, MYSQLI_ASSOC);
				$id_usuario = $ok1['id'];
				$sql = " INSERT INTO config(id_user) values ('$id_usuario') ";
				if(mysqli_query($link,$sql)){
					$emailOk = addslashes($email);
				}else{
					$_SESSION['jooj'] = 'Erro ao se conectar com o servidor. Contate o Dev.';
					echo '<script>window.location.href="cadastrar"</script>';
					die();
				}
			}else{
				$_SESSION['jooj'] = 'Erro ao se conectar com o servidor. Contate o Dev.';
				echo '<script>window.location.href="cadastrar"</script>';
				die();
			}
		}else{
			$_SESSION['jooj'] = 'Erro ao se conectar com o servidor. Contate o Dev.';
			echo '<script>window.location.href="cadastrar"</script>';
			die();
		}

//Include required PHPMailer files
	require 'includes/PHPMailer.php';
	require 'includes/SMTP.php';
	require 'includes/Exception.php';
//Define name spaces
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;
//Create instance of PHPMailer
	$mail = new PHPMailer();
//Set mailer to use smtp
	$mail->isSMTP();
//Define smtp host
	$mail->Host = "smtp.gmail.com";
//Enable smtp authentication
	$mail->SMTPAuth = true;
//Set smtp encryption type (ssl/tls)
	$mail->SMTPSecure = "tls";
//Port to connect smtp
	$mail->Port = "587";
//Set gmail username
	$mail->Username = "suporte.thonthon@gmail.com";
//Set gmail password
	$mail->Password = "";
//Email subject
	$mail->Subject = "Ativacao de Email- Thonthon";
//Set sender email
	$mail->setFrom('suporte.thonthon@gmail.com');
//Enable HTML
	$mail->isHTML(true);
	$body = '
				<html>
				<body style="margin:0;bottom:0;width:100%;background-color:#fff;">

								<div style="bottom:0;width:100%;text-align:center;line-height:40px;font-size:25px;margin-bottom:10px;margin-top:30px;">
									<span style="color:#404577;text-decoration:none;font-family:Arvo,Courier,Georgia,serif;color:#090909">Clique no Botão Abaixo Para Ativar Sua Conta</strong></u></a>
								</div>

								</br>


								<div style="bottom:0;width:100%;text-align:center;line-height:40px;font-size:15px;margin-bottom:10px;">
								<a href="http://localhost/thonthon/confirmation?type=activate&email='.$email.'" target="_blank" style="background-color:#171a1b;color:#ffffff;text-decoration:none;padding:12px 20px;border-radius:5px;font-weight:bold;border-width:2px;border-color:#ffffff;font-family:Arvo,Courier,Georgia,serif;"> Ativar
								</a>
								</div>
								<div style="bottom:0;width:100%;text-align:center;line-height:40px;font-size:25px;">
								<img style="width:100%;" src="https://cdn.discordapp.com/attachments/731269982138269736/1017091153226633367/ScreenShot_20220907121602-min.png"></img>
								</div>


								</body>
								</html>';
	$mail->Body = $body;
	$mail->addAddress(''.$emailOk.'');
	if ( $mail->send() ) {
			$_SESSION['gg'] = 'Uma mensagem de ativação de conta foi enviada ao Email inserido!';
			echo '<script>window.location.href="cadastrar"</script>';
	}else{
		$sql = " DELETE FROM `usuarios` WHERE `usuarios`.`id` = $id_usuario ";
			if(mysqli_query($link,$sql)){
				$sql = " DELETE FROM `config` WHERE `config`.`id_ser` = $id_usuario ";
				if(mysqli_query($link,$sql)){
					$_SESSION['jooj'] = 'Erro ao enviar o email de Ativação.';
					echo '<script>window.location.href="cadastrar"</script>';
				}else{
					$_SESSION['jooj'] = 'Erro ao se conectar com o servidor. Contate o Dev. #3M31L';
					echo '<script>window.location.href="cadastrar"</script>';
					die();					
				}
			}else{
				$_SESSION['jooj'] = 'Erro ao se conectar com o servidor. Contate o Dev. #3M31L';
				echo '<script>window.location.href="cadastrar"</script>';
				die();
			}
	}
//Closing smtp connection
	$mail->smtpClose();
				?>