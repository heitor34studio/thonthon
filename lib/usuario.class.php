<?php
	session_start();
	require_once('db.class.php');


	//FUNÇÃO LOGIN
	function loginUser(){
		$objDB = new db();
		$link = $objDB->conecta_mysql();
		$usuario = addslashes($_POST['user']);
		$senha =  addslashes($_POST['password']);
		$pass_size2 = strlen($usuario);
		$user_size2 = strlen($senha);
		if($pass_size2 > 60 || $user_size2 > 50){
			$_SESSION['jooj'] = 'Os dados inseridos ultrapassam o limite de caracteres.';
			header('Location: ../login');
			die();
		}
		$sql = " SELECT * FROM usuarios WHERE (usuario = '$usuario' OR email = '$usuario')";
		$resultado_id = mysqli_query($link, $sql);
		if($resultado_id){
			$dados_usuario = mysqli_fetch_array($resultado_id);
			if(password_verify($senha,$dados_usuario['senha'])){
				if($dados_usuario['ATIVADO'] == 'NAO'){
					$_SESSION['jooj'] = 'Esta conta não está ativada. Cheque as mensagens (e a caixa de spam) do email inserido.';
					header('Location: ../login');
					die();
				}else{
					$_SESSION['userEmail'] = $dados_usuario['email'];
					$_SESSION['id_usuario'] = $dados_usuario['id'];
					$_SESSION['userName'] = $dados_usuario['usuario'];
					$_SESSION['tema'] = $dados_usuario['tema'];
					$_SESSION['bio'] = $dados_usuario['biografia'];
					$_SESSION['pfp'] = $dados_usuario['pfp'];
					header('Location: ../home');
				}
			}else{
				$_SESSION['jooj'] = 'A senha ou o nome de usuário/email está incorreto.';
				header('Location: ../login');
				die();
			}
		}else{
			$_SESSION['jooj'] = 'Erro ao conectar com o servidor.';
			header('Location: ../login');
			die();
		}
	}


	//FUNÇÃO DIMINUI/AUMENTA IMAGENS 500x500
	function mudaPra500(){
		if(!isset($_SESSION['id_usuario'])){
		    header('Location: login');
		}
		$id_usuario = addslashes($_SESSION['id_usuario']);
		$objDB = new db();
        $link = $objDB->conecta_mysql();
        $sql = " SELECT * FROM `usuarios` WHERE `usuarios`.`id` = $id_usuario ";
		$query = mysqli_query($link, $sql);
		if($query){
			$registro_query = mysqli_fetch_array($query, MYSQLI_ASSOC);
			$default_pfps = array("1.jpg","2.jpg","3.jpg","4.jpg","5.jpg");
		    $pfp_do_sujeito = $registro_query['pfp'];
			if(in_array($pfp_do_sujeito, $default_pfps)){
				die();
			}else{
					//LER A IMAGEM
				$PFP = pathinfo($pfp_do_sujeito, PATHINFO_EXTENSION);
				$PFP_lc = strtolower($PFP);
				if($PFP_lc == 'jpg' || $PFP_lc == 'jpeg'){
					// Load the image
					$image = imagecreatefromjpeg("../img/user".$pfp_do_sujeito);

					// Get the image dimensions
					$width = imagesx($image);
					$height = imagesy($image);

					// Calculate the center coordinates
					$centerX = round($width / 2);
					$centerY = round($height / 2);

					// Set the dimensions of the center region
					$regionWidth = 500; // Change this to your desired width
					$regionHeight = 500; // Change this to your desired height

					// Calculate the top-left corner coordinates of the center region
					$topLeftX = round($centerX - ($regionWidth / 2));
					$topLeftY = round($centerY - ($regionHeight / 2));

					// Create a new image that is the same size as the center region
					$croppedImage = imagecreatetruecolor($regionWidth, $regionHeight);

					// Copy the center region from the original image to the new image
					imagecopy($croppedImage, $image, 0, 0, $centerX, $centerY, $regionWidth, $regionHeight);

					// Output the cropped image to the browser
					imagejpeg($croppedImage, "../img/user".$pfp_do_sujeito);

					// Clean up
					imagedestroy($image);
					imagedestroy($croppedImage);



					
					$original = imagecreatefromjpeg("../img/user".$pfp_do_sujeito);
					$fileinfo = @getimagesize("../img/user".$pfp_do_sujeito);
			    	$width = $fileinfo[0];
			    	$height = $fileinfo[1];
						//CRIAR TEMPLATE
					$resized = imagecreatetruecolor(500, 500);
						//RECRIAR TAMANHO
					imagecopyresampled($resized, $original, 0, 0, 0, 0, 500, 500, $width, $height);
						//SALVAR IMAGEM E REMOVER A ANTIGA
					$filename = '../img/user'.$pfp_do_sujeito;
					unlink($filename);
					imagejpeg($resized, "../img/user".$pfp_do_sujeito);
					
				}elseif($PFP_lc == 'png'){
					$original = imagecreatefrompng("../img/user".$pfp_do_sujeito);
					$fileinfo = @getimagesize("../img/user".$pfp_do_sujeito);
			    	$width = $fileinfo[0];
			    	$height = $fileinfo[1];
						//CRIAR TEMPLATE
					$resized = imagecreatetruecolor(500, 500); // SMALLER BY 50%

						//CRIANDO CAMADA TRANSAPARENTE
					imagealphablending($resized, false);
					imagesavealpha($resized, true);
					imagefilledrectangle(
					  $resized, 0, 0, 500, 500, 
					  imagecolorallocatealpha($resized, 255, 255, 255, 127)
					);
						//RECRIAR TAMANHO
					imagecopyresampled($resized, $original, 0, 0, 0, 0, 500, 500, $width, $height);
						//SALVAR IMAGEM E REMOVER A ANTIGA
					$filename = '../img/user'.$pfp_do_sujeito;
					unlink($filename);
					imagejpeg($resized, "../img/user".$pfp_do_sujeito);
				}
			}
		}
	}


	//FUNÇÃO INSERE FOTO SERVIDOR e CHECA PROFILE etc
	function alteraImage(){
		if(!isset($_SESSION['id_usuario'])){
		    header('Location: login');
		}
		$id_usuario = addslashes($_SESSION['id_usuario']);
		$objDB = new db();
        $link = $objDB->conecta_mysql();
		$folderPath = '../img/';
		$image_parts = explode(";base64,", $_POST['image']);
		$image_type_aux = explode("image/", $image_parts[0]);
		$image_type = $image_type_aux[1];
		$image_base64 = base64_decode($image_parts[1]);
		$new_img_name = uniqid("user", true) . '.png';
		$file = $folderPath . $new_img_name;
		$upload = file_put_contents($file, $image_base64);
		if(!$upload){
			$response = array(
				'status' => false
			);
			echo json_encode($response);
			die();
		}
		$sql = " SELECT * FROM `usuarios` WHERE `usuarios`.`id` = $id_usuario ";
		$query = mysqli_query($link, $sql);
		if($query){
			$registro_query = mysqli_fetch_array($query, MYSQLI_ASSOC);
			$default_pfps = array("1.jpg","2.jpg","3.jpg","4.jpg","5.jpg");
		    $pfp_do_sujeito = $registro_query['pfp'];
			if(in_array($pfp_do_sujeito, $default_pfps)){
				$new_img_name_to_insert = substr($new_img_name, 4);
				$sql = " UPDATE `usuarios` SET `pfp` = '$new_img_name_to_insert' WHERE `usuarios`.`id` = $id_usuario ";
				if(mysqli_query($link, $sql)){
					$_SESSION['pfp'] = $new_img_name_to_insert;
					mudaPra500();
					$response = array(
						'status' => true
					);
					echo json_encode($response);
					die();
				}
			}else{
				$pfp_do_sujeito = 'user'.$pfp_do_sujeito;
				$filename = '../img/'.$pfp_do_sujeito;
				if(file_exists($filename)){
					unlink($filename);
					$new_img_name_to_insert = substr($new_img_name, 4);
					$sql = " UPDATE `usuarios` SET `pfp` = '$new_img_name_to_insert' WHERE `usuarios`.`id` = $id_usuario ";
					if(mysqli_query($link, $sql)){
						$_SESSION['pfp'] = $new_img_name_to_insert;
						mudaPra500();
						$response = array(
							'status' => true
						);
						echo json_encode($response);
						die();
					}else{
						unlink('..img/'.$new_img_name);
						$response = array(
							'status' => false,
							'msg' => "Ocorreu um erro ao remover sua imagem personalizada antiga. Contate o Dev."
						);
						echo json_encode($response);
						die();
					}
				}else{
					unlink('..img/'.$new_img_name);
					$response = array(
						'status' => false,
						'msg' => "Ocorreu um erro ao remover sua imagem personalizada antiga. Contate o Dev."
					);
					echo json_encode($response);
					die();
				}
			}
		}else{
			unlink('..img/'.$new_img_name);
			$response = array(
				'status' => false,
				'msg' => "Ocorreu um erro ao contatar o servidor. Contate o Dev."
			);
			echo json_encode($response);
			die();
		}
		/*
		if(isset($_POST['submitImg']) && isset($_FILES['image'])){
			$img_name = $_FILES['image']['name'];
			$img_size = $_FILES['image']['size'];
			$img_tmp_name = $_FILES['image']['tmp_name'];
			$img_error = $_FILES['image']['error'];
			if($img_error === 0){
				if($img_size > 1000000){
					$em = "Sua imagem não deve passar de 1MB.";
					header("Location: ../config?focus=avatar&erro=$em");
				}else{
					$img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
					$img_ex_lc = strtolower($img_ex);
					$allowed_exs = array("jpg","jpeg","png");
					if(in_array($img_ex_lc, $allowed_exs)){
						$new_img_name = uniqid("user", true).".".$img_ex_lc;
						$img_upload_path = '../img/'.$new_img_name;
						if(move_uploaded_file($img_tmp_name, $img_upload_path)){
							
		                    }
						}else{
							$em = "Não foi possível subir o arquivo.";
							header("Location: ../config?focus=avatar&erro=$em");
							die();
						}
					}else{
						$em = "Extensão não permitida.";
						header("Location: ../config?focus=avatar&erro=$em");
					}
				}
			}else{
				$em = "Um erro desconhecido ocorreu.";
				header("Location: ../config?focus=avatar&erro=$em");
			}
		}else{
			$em = "Um erro desconhecido ocorreu.";
			header("Location: ../config?focus=avatar&erro=$em");
		}
		*/
	}


	//FUNÇÃO LOGOUT
	function logOutUser(){
		$_SESSION = array();
		header('Location: ../index');
	}


	if(isset($_POST['user'])){
		loginUser();
	}elseif(isset($_POST['submitImg'])){
		alteraImage();
	}else{
		logOutUser();
	}
?>