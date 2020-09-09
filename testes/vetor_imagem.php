<?php
	/**
	 * Testes unitários do vetor Imagem
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */

	$imagemVetor = array(["ID" => 1, "IMAGEM" => "jantar2019-posse1.jpg", "EVENTO" => 1, "STATUS" => TRUE], ["ID" => 2, "IMAGEM" => "jantar2019-posse2.jpg", "EVENTO" => 1, "STATUS" => TRUE], ["ID" => 3, "IMAGEM" => "jantar2019-posse3.jpg", "EVENTO" => 1, "STATUS" => TRUE], ["ID" => 4, "IMAGEM" => "jantar2019-posse4.jpg", "EVENTO" => 1, "STATUS" => TRUE], ["ID" => 5, "IMAGEM" => "jantar2019-posse5.jpg", "EVENTO" => 1, "STATUS" => TRUE], ["ID" => 6, "IMAGEM" => "jantar2019-posse6.jpg", "EVENTO" => 1, "STATUS" => TRUE], ["ID" => 7, "IMAGEM" => "jantar2019-posse7.jpg", "EVENTO" => 1, "STATUS" => TRUE], ["ID" => 8, "IMAGEM" => "jantar2019-posse8.jpg", "EVENTO" => 1, "STATUS" => TRUE], ["ID" => 9, "IMAGEM" => "jantar2019-posse9.jpg", "EVENTO" => 1, "STATUS" => TRUE], ["ID" => 10, "IMAGEM" => "jantar2019-posse10.jpg", "EVENTO" => 1, "STATUS" => TRUE]);

	// DEBUG MODE
	if((isset($argv[1]) && $argv[0] == "vetor_imagem.php" && $argv[1] == "debug") || (isset($argv[1]) && $argv[1] == "debug-all")) {
		print_r($imagemVetor);
	}
?>