<?php
class db{
	//host
	private $host = 'localhost';
	//usuario
	private $usuario = 'root';
	//senha
	private $senha = '';
	//banco de dados
	private $database = 'test';
	//função
	public function conecta_mysql(){
		//criar conexão
		$con = mysqli_connect($this->host, $this->usuario, $this->senha, $this->database);
		//ajustar o charset de comunicação entra a aplicação eo banco de dados
		mysqli_set_charset($con, 'utf8');
		//verificar se houve erro de conexão
		if(mysqli_connect_errno()){
			echo 'Erro ao tentar conectar com os servidores: '.mysql_connect_error();
		}
		return $con;
	}
}
?>