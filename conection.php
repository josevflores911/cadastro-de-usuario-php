<?php
define('HOST','127.0.0.1');
define('USUARIO','root');
define('SENHA','123456');
define('DB','wanfer');

$conexao=mysqli_connect(HOST,USUARIO,SENHA,DB) or die('Nao foi possivel conectar');
?>
