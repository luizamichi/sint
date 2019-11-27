<?php

	// CARREGA TODAS AS CONSTANTES PRÉ-DEFINIDAS
	require_once(__DIR__ . '/load.php');

	// ROTA RETORNA APENAS JSON COMO RESPOSTA
	header('Content-type: application/json; charset=utf-8');

	// VERIFICA SE O USUÁRIO ESTÁ AUTENTICADO
	if(LOGGED_USER) {
		$logout = (int) (GET_PARAMS['LOGOUT'] ?? 0);
		$renew = (int) (GET_PARAMS['RENEW'] ?? 0);

		http_response_code(200);

		// O USUÁRIO SOLICITOU UMA RENOVAÇÃO DE SESSÃO
		if($renew === 1) {
			$email = LOGGED_USER['EMAIL'];
			$password = LOGGED_USER['SENHA'];

			$user = sqlRead(table: 'USUARIOS', condition: 'EMAIL = "' . $email . '" AND SENHA = "' . $password . '"', unique: true);
			if(isset($user['EMAIL'], $user['SENHA']) && $user['EMAIL'] === $email && $user['SENHA'] === $password) {
				$_SESSION['USER'] = serialize($user);
				exit(json_encode([
					'message' => 'Renovação de sessão efetuada com sucesso!'
				]));
			}

			$logout = 1;
		}

		if($logout === 1) {
			session_unset();
			session_destroy();
			saveLog('LOGOUT', LOGGED_USER['ID']);
			exit(json_encode([
				'message' => 'Você foi desconectado.'
			]));
		}
	}

	elseif(REQUEST_METHOD === 'POST') {
		$email = filter_var(POST_PARAMS['EMAIL'] ?? '', FILTER_DEFAULT, FILTER_VALIDATE_EMAIL);
		$password = filter_var(POST_PARAMS['PASSWORD'] ?? '', FILTER_DEFAULT);

		$user = sqlRead(table: 'USUARIOS', condition: 'EMAIL = "' . antiInjection($email) . '" AND SENHA = "' . md5($password) . '"', unique: true);
		if(isset($user['EMAIL'], $user['SENHA']) && $user['EMAIL'] === $email && $user['SENHA'] === md5($password)) {
			$_SESSION['USER'] = serialize($user);
			$_SESSION['ACTIVE_TIME'] = time();

			saveLog('LOGIN', $user['ID'], $_SERVER['HTTP_USER_AGENT'] ?? null);
			http_response_code(200);
			exit(json_encode([
				'message' => 'Autenticação efetuada com sucesso!'
			]));
		}

		else {
			http_response_code(401);
			exit(json_encode([
				'message' => 'Não foi possível autenticar-se. Verifique o e-mail e a senha!'
			]));
		}
	}

	http_response_code(400);
	exit(json_encode([
		'message' => 'Requisição inválida.'
	]));
