<?php
session_start();
require_once('../lib/db.class.php');
$contactEmail = addslashes($_POST['email']);
if(strlen($contactEmail) > 50){
	echo '<span id="successR" class="btn btn-outline-danger m-2">Erro. Este email ultrapassa o limite de caracteres permitidos.</span>';
	echo '<script id="scrip">$("#successR").click(function(){$("#successR").remove();$("#scrip").remove();}) </script>';
	die();
}
$objDB = new db();
$link = $objDB->conecta_mysql();



$sql = " SELECT COUNT(*) AS jaexiste FROM usuarios  WHERE email = '$contactEmail' ";
$emailExiste = mysqli_query($link, $sql);
if($emailExiste){
	$qntd = mysqli_fetch_array($emailExiste, MYSQLI_ASSOC);
	if($qntd['jaexiste'] == 1){
		echo '<span id="successR" class="btn btn-outline-danger m-2">Erro. Este email já está sendo utilizado.</span>';
		echo '<script id="scrip">$("#successR").click(function(){$("#successR").remove();$("#scrip").remove();}) </script>';
		die();
	}
}else{
	echo 'Erro ao se conectar com o servidor.';
	die();
}



date_default_timezone_set('America/Sao_Paulo');
$date = new DateTime(date("Y-m-d H:i:s", time()));
$tempo = $date->format('20y-m-d H:i:s');
$id_usuario = $_SESSION['id_usuario'];
$sql = " SELECT * FROM config WHERE id_user = $id_usuario ";
$uuu = mysqli_query($link, $sql);



if($uuu){
	$registroUUU = mysqli_fetch_array($uuu, MYSQLI_ASSOC);
	if(isset($registroUUU['horaAlteracaoE'])){
			$date2 = new DateTime(date($registroUUU['horaAlteracaoE']));
			$interval = $date->diff($date2);
			$total = $interval->format('%m') *43200 + $interval->format('%d') * 1440 + $interval->format('%h') * 60 + $interval->format('%i');
			if($total < 60){
				echo '<span id="successR" class="btn btn-outline-danger m-2">Você tem que esperar 1 hora para poder alterar seu email de novo. (Cheque a caixa de Spam)</span>';
				echo '<script id="scrip">$("#successR").click(function(){$("#successR").remove();$("#scrip").remove();}) </script>';
				die();
			}
	}
}else{
	echo 'Erro ao se conectar com o servidor. #DateTime';
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
	$mail->Subject = "Troca de Email- Thonthon";
//Set sender email
	$mail->setFrom('suporte.thonthon@gmail.com');
//Enable HTML
	$mail->isHTML(true);
//Attachment
//$mail->addAttachment('img/attachment.png');
//Email body
	if(isset($_SESSION['userName'])){
		$userName1 = addslashes($_SESSION['userName']);
	}else{
		$userName1 = 'Erro #001.';	
	}
	$type = addslashes($_POST['type']);
	if($type == 'changeEmail'){
		$body = '
				<html>
					<body style="margin:0;bottom:0;width:100%;background-color:#fff;">

					<div style="bottom:0;width:100%;text-align:center;line-height:40px;font-size:25px;margin-bottom:10px;margin-top:30px;">
						<span style="color:#404577;text-decoration:none;font-family:Arvo,Courier,Georgia,serif;color:#090909">Clique no Botão Abaixo Para Confirmar a Troca de Email de <u><strong>'.$userName1.'</strong></u></a>
					</div>

					</br>


					<div style="bottom:0;width:100%;text-align:center;line-height:40px;font-size:15px;margin-bottom:10px;">
					<a href="http://localhost/thonthon/confirmation?type=newEmail&id='.$id_usuario.'&newEmail='.$contactEmail.'" target="_blank" style="background-color:#171a1b;color:#ffffff;text-decoration:none;padding:12px 20px;border-radius:5px;font-weight:bold;border-width:2px;border-color:#ffffff;font-family:Arvo,Courier,Georgia,serif;"> Alterar
					</a>
					</div>
					<div style="bottom:0;width:100%;text-align:center;line-height:40px;font-size:25px;">
					<img style="width:100%;" src="https://cdn.discordapp.com/attachments/731269982138269736/1017091153226633367/ScreenShot_20220907121602-min.png"></img>
					</div>


					</body>
					</html>';
	}


	$mail->Body = $body;
//Add recipient
	$mail->addAddress($contactEmail);
//Finally send email
	if ( $mail->send() ) {
		echo '<span id="successR" class="btn btn-outline-success m-2">Successo! Uma mensagem de confirmação foi enviada ao Email inserido. Você tem 1 hora para confirmar. (Cheque a caixa de Spam)</span>';
		echo '<script id="scrip">$("#successR").click(function(){$("#successR").remove();$("#scrip").remove();}) </script>';
		$sql = " UPDATE `config` SET `emTrocaEmail` = 'SIM', `horaAlteracaoE` = '$tempo' WHERE `config`.`id_user` = $id_usuario ";
		$resultado_id = mysqli_query($link, $sql);
		if(!$resultado_id){
			echo 'Erro ao se conectar com o servidor. #Update-DateTime';
		}
	}else{
		echo "Message could not be sent.";
	}
//Closing smtp connection
	$mail->smtpClose();
?>