<?php
	//FUNÇÃU USUARIO
    require_once('db.class.php');
	$usuario = addslashes($_POST['valueUser']);
	$jooj = strlen($usuario);
	if($usuario == ''){
		$resUser = '';
		$userOk = 1;
	}else{
		if($jooj < 4){
			$resUser = '<font style="color:#808080" size="-1"> O nome de usuário deve conter ao menos 4 caracteres.</font>';
			$userOk = 1;
		}else{
			$objDB = new db();
			$link = $objDB->conecta_mysql();
			$sql = "SELECT COUNT(*) AS qntd_ids FROM usuarios WHERE usuario = '$usuario' ";
			$qntd_ids = 0;
			$resultado_id = mysqli_query($link, $sql);
			if($resultado_id){
				$registro = mysqli_fetch_array($resultado_id, MYSQLI_ASSOC);
				$qntd_ids = $registro['qntd_ids'];
			}
			if($qntd_ids != 0){
				$resUser = '<font style="color:#ff0000"> Este usuário já está sendo utilizado.</font>';
				$userOk = 1;
			}else{
				$resUser = '';
				$userOk = 0;
			}
		};
	}
	


	//FUNÇÃU EMAIL
	$email = addslashes($_POST['valueEmail']);
	$objDB = new db();
	$link = $objDB->conecta_mysql();
	$sql = "SELECT COUNT(*) AS qntd_ids FROM usuarios WHERE email = '$email' ";
	$qntd_ids = 0;
	$resultado_id = mysqli_query($link, $sql);
	if($resultado_id){
		$registro = mysqli_fetch_array($resultado_id, MYSQLI_ASSOC);
		$qntd_ids = $registro['qntd_ids'];
	}
	if($qntd_ids != 0){
		$resEmail = '<font style="color:#ff0000"> Este email já está sendo utilizado.</font>';
		$emailOk = 1;
	}else{
		$resEmail = '';
		$emailOk = 0;
	}


	//FUNÇÃU SENHA
    $senha = addslashes($_POST['valuePass']);
	$senhaLen = strlen($senha);
	if($senha == ''){
		$resPass = '';
		$passOk = 1;
	}else{
		if($senhaLen < 8){
			$resPass = '<font style="color:#808080" size="-1"> A senha deve conter ao menos 8 caracteres.</font>';
			$passOk = 1;
		}else{
			$resPass = '';
			$passOk = 0;
		}
	}


	//FUNÇÃU SENHA2
	$senha2 = addslashes($_POST['valuePass2']);
	if($senha == ''){
		$resPass2 = '';
		$pass2Ok = 1;
	}else{
		if($senha == $senha2){
			$resPass2 = '';
			$pass2Ok = 0;
		}else{
			$resPass2 = '<font style="color:#ff0000"> As senhas não coincidem.</font>';
			$pass2Ok = 1;
		}
	}

//EXIBINDO O RESULTADO DA FUNÇÃO ESPECÍFCA QUE FEZ ESSE REQUEST
//USER
$tipo = addslashes($_POST['type']);
if($tipo == 'floatingText'){
	echo $resUser;
	if($resEmail != ''){
		if($resUser != '' || $resPass != '' || $resPass2 != ''){
			echo '<br>';
			echo $resEmail;
		}else{
			echo $resEmail;
		}
	}

	if($resPass != ''){
		if($resUser != '' || $resEmail != '' || $resPass2 != ''){
			echo '<br>';
			echo $resPass;
		}else{
			echo $resPass;
		}
	}

	if($resPass2 != ''){
		if($resPass != '' || $resUser != '' || $resEmail != ''){
			echo '<br>';
			echo $resPass2;
		}else{
			echo $resPass2;
		}
	}
}

//EMAIL
if($tipo == 'floatingInput'){
	echo $resEmail;
	if($resUser != ''){
		if($resEmail != '' || $resPass != '' || $resPass2 != ''){
			echo '<br>';
			echo $resUser;
		}else{
			echo $resUser;
		}
	}

	if($resPass != ''){
		if($resUser != '' || $resEmail != '' || $resPass2 != ''){
			echo '<br>';
			echo $resPass;
		}else{
			echo $resPass;
		}
	}

	if($resPass2 != ''){
		if($resPass != '' || $resUser != '' || $resEmail != ''){
			echo '<br>';
			echo $resPass2;
		}else{
			echo $resPass2;
		}
	}
}

//SENHA
if($tipo == 'floatingPassword'){
	echo $resPass;
	if($resUser != ''){
		if($resEmail != '' || $resPass != '' || $resPass2 != ''){
			echo '<br>';
			echo $resUser;
		}else{
			echo $resUser;
		}
	}

	if($resEmail != ''){
		if($resUser != '' || $resPass != '' || $resPass2 != ''){
			echo '<br>';
			echo $resEmail;
		}else{
			echo $resEmail;
		}
	}

	if(strlen($senha2) != 0){
		if($resPass != '' || $resUser != '' || $resEmail != ''){
			echo '<br>';
			echo $resPass2;
		}else{
			echo $resPass2;
		}
	}
}

//SENHA2
if($tipo == 'floatingPassword2'){
	echo $resPass2;
	if($resUser != ''){
		if($resEmail != '' || $resPass != '' || $resPass2 != ''){
			echo '<br>';
			echo $resUser;
		}else{
			echo $resUser;
		}
	}

	if($resEmail != ''){
		if($resUser != '' || $resPass != '' || $resPass2 != ''){
			echo '<br>';
			echo $resEmail;
		}else{
			echo $resEmail;
		}
	}
	
	if($resPass != ''){
		if($resUser != '' || $resEmail != '' || $resPass2 != ''){
			echo '<br>';
			echo $resPass;
		}else{
			echo $resPass;
		}
	}
}

//LIBERANDO OU NÃO O BOTÃO
if($userOk == 0 && $emailOk == 0 && $passOk == 0 && $pass2Ok == 0){
	echo '<script class="autoRemove">
			if(1 == 1){
				$("#zZz").prop("disabled", false);
				$(".autoRemove").remove();
			}
		</script>';

	echo 
		"<script>
			$('#zZz').click(function(){
                $('#zZz').css('display', 'none');
                $('#load').css('display', 'block');
                var valueUser = $('#floatingText').val();
                var valueEmail = $('#floatingInput').val();
                var valuePass = $('#floatingPassword').val();
                var valuePass2 = $('#floatingPassword2').val();
                var type = this.id;
                $.ajax({
                    url: 'lib/email.php',
                    method: 'post',
                    data: {valueUser: valueUser,valueEmail: valueEmail,valuePass: valuePass,valuePass2: valuePass2,type: type},
                    success: function(data){
                        $('#erroDiverso').val('');
                        $('#erroDiverso').html(data);
                    }
                });
            })
        </script>";
}else{
	echo '<script class="autoRemove">
			if(1 == 1){
				$("#zZz").prop("disabled", true);
				$(".autoRemove").remove();
			}
		</script>';
}
?>