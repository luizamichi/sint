<?php
	/**
	 * Testes unitários do modelo Usuário
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("../modelos/Usuario.php");
	include_once("modelo_permissao.php");

	$usuarioModelo = new Usuario();
	$usuarioModelo->setId(1);
	$usuarioModelo->setNome("Luiz Joaquim Aderaldo Amichi");
	$usuarioModelo->setEmail("luizamichi@luizamichi.com.br");
	$usuarioModelo->setLogin("luizjoaquim");
	$usuarioModelo->hashSenha("51nt3Em@r4dminLuIZj0@qu1M"); // $usuarioModelo->setSenha("f8aac0d330da589187edd16415fb3a92");
	$usuarioModelo->setPermissao($permissaoModelo);
	$usuarioModelo->setStatus(TRUE);

	// DEBUG MODE
	if((isset($argv[1]) && $argv[0] == "modelo_usuario.php" && $argv[1] == "debug") || (isset($argv[1]) && $argv[1] == "debug-all")) {
		echo $usuarioModelo;
	}
?>