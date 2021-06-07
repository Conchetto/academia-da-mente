<?php

	
	include_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );


	$action = (!empty($_GET['action']) && isset($_GET['action'])) ? $_GET['action'] : 0;
	$content = (!empty($_GET['content']) && isset($_GET['content'])) ? $_GET['content'] : 0;

	switch ($action) {
		case 'checkVoucher':
			checkVoucher($content);
			break;
		case 'addUser':
			addUser($content);
			break;
		case 'checkEmail':
			checkEmail($content);
			break;
		default:
			# code...
			break;
	}

	function checkVoucher($content) {
		global $wpdb;

	  	// $select = "select id from conteudo_concluido where id_user = '{$_POST['offset']['id_user']}' and id_conteudo = '{$_POST['offset']['id_conteudo']}'";
	  	$select = "select codigo from wp_vouchers where codigo = '{$content}'";
		$consulta = $wpdb->get_results($select);

		if($consulta)
			echo json_encode(true);
		else 
			echo json_encode(false);

	}

	function addUser($newUser) {

		global $wpdb;

		if ( ! username_exists( $newUser['voucher'] ) ) {
			$user_id = wp_create_user( $newUser['email'], $newUser['password'], $newUser['email'] );
			$user = new WP_User( $user_id );
			$user->set_role( 'contributor' );

			if ($user_id > 0) {
				$update = "Update wp_users
						  Set user_nicename = '{$newUser['name']}'
						  where id = {$user_id}";
				$wpdb->query($update);


				$creds = array(
			        'user_login'    => $newUser['email'],
			        'user_password' => $newUser['password'],
			        'remember'      => true
			    );
			 
			    $user = wp_signon( $creds, false );
			 
			    if ( is_wp_error( $user ) ) {
			        echo $user->get_error_message();
			    }

				$trilhas = (get_site_url().'/trilhas');
				echo $trilhas;
			}

			// $select = "select id from conteudo_concluido where id_user = '{$_POST['offset']['id_user']}' and id_conteudo = '{$_POST['offset']['id_conteudo']}'";
		  	$query = "insert into wp_users_vouchers (id_user, voucher) values ('{$user_id}', '{$newUser['voucher']}')";
			$wpdb->query($query);

			$to = $newUser['email'];
			$subject = 'Novo Cadastro';
			$message = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum';

			wp_mail( $to, $subject, $message );

		}
	}

	function checkEmail($content) {
		global $wpdb;

	  	// $select = "select id from conteudo_concluido where id_user = '{$_POST['offset']['id_user']}' and id_conteudo = '{$_POST['offset']['id_conteudo']}'";
	  	$select = "select user_email from wp_users where user_email = '{$content}'";
		$consulta = $wpdb->get_results($select);

		if($consulta)
			echo json_encode(true);
		else 
			echo json_encode(false);

	}
