<?php
ini_set('display_errors','On');
ini_set('error_reporting', E_ALL );
	
	// set the array for testing the local environment
	$whitelist = array( '127.0.0.1', '::1' );
	
	// check if the server is in the array
	if ( in_array( $_SERVER['REMOTE_ADDR'], $whitelist ) ) {
		
		include_once($_SERVER['DOCUMENT_ROOT'].'/academia_mente/wp-load.php' );
		
		
	}else{
		include_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );

	}

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	// require 'PHPMailer/src/Exception.php';
	// require 'PHPMailer/src/PHPMailer.php';
	// require 'PHPMailer/src/SMTP.php';


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
	  	$select = "select codigo from wp_vouchers where codigo = '{$content}' and deleted = 'false'";

		$consulta = $wpdb->get_results($select);

		if($consulta)
			echo json_encode(true);
		else 
			echo json_encode(false);

		die();

	}

	function addUser($newUser) {

		global $wpdb;

		if ( ! username_exists( $newUser['voucher'] ) ) {
			$user_id = wp_create_user( $newUser['email'], $newUser['password'], $newUser['email'] );
			$user = new WP_User( $user_id );
			$user->set_role( 'contributor' );


			$cpf = preg_replace("/[^0-9]/", "", $newUser['cpf']);

			$update = "update cpfs set id_user = {$user_id} where id =  {$newUser['cpfID']}";
			$wpdb->query($update);


			// $select = "select id from conteudo_concluido where id_user = '{$_POST['offset']['id_user']}' and id_conteudo = '{$_POST['offset']['id_conteudo']}'";
		  	$query = "insert into wp_users_vouchers (id_user, voucher,cpf) values ('{$user_id}', '{$newUser['voucher']}','{$cpf}')";
			$wpdb->query($query);

			$to = $newUser['email'];
			// wp_mail( $to, $subject, $message );

			sendEmail($to, $newUser['name']);

			if ($user_id > 0) {
				$update_ = "Update wp_users
						  Set user_nicename = '{$newUser['name']}'
						  where id = '{$user_id}'";
				$wpdb->query($update_);


				$creds = array(
			        'user_login'    => $newUser['email'],
			        'user_password' => $newUser['password'],
			        'remember'      => true
			    );
			 
			    $user = wp_signon( $creds, false );
			 
			    if ( is_wp_error( $user ) ) {
			        echo $user->get_error_message();
			    }

				$trilhas = (get_site_url());
				echo $trilhas;
			}

		}

		// die();
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

		die();

	}


	function sendEmail($to, $name) {
		// Instantiation and passing `true` enables exceptions
		$mail = new PHPMailer(true);

		try {
		    //Server settings
		    //$mail->SMTPDebug = 2;                      // Enable verbose debug output
		    $mail->isSMTP();                                            // Send using SMTP
		    $mail->Host       = 'smtp.ecare.group';                    // Set the SMTP server to send through
		    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
		    $mail->Username   = 'academia@ecare.group';                     // SMTP username
		    $mail->Password   = 'Academia!@#456';                               // SMTP password
		    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
		    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
			$mail->CharSet = 'UTF-8';

		    //Recipients
		    $mail->setFrom('academia@ecare.group', 'Academia da Mente');
		    $mail->addAddress($to, $name);     // Add a recipient
		    // $mail->addAddress('ellen@example.com');               // Name is optional
		    // $mail->addReplyTo('info@example.com', 'Information');
		    // $mail->addCC('cc@example.com');
		    // $mail->addBCC('bcc@example.com');

		    // Attachments
		    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

		    // Content
		    $mail->isHTML(true);                                  // Set email format to HTML
		    $mail->Subject = 'Agora você faz parte da Academia da Mente!';
		    $mail->Body    = '
			<html>
			<head>
				<meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
				<meta content="IE=edge" http-equiv="X-UA-Compatible"/>
				<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
				<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet" type="text/css"/>
			</head>
				<body style="background:#fff">
					<table  bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0"  style="max-width:600px;width: 600px;margin:0 auto;">
						<tr>
							<td colspan="3" style="padding:50px 0;text-align: center;"><img src="http://academia.clinicaecare.com.br/wp-content/uploads/2020/10/logo-academia-da-mente.png" alt=""></td>
						</tr>
						<tr><td colspan="3"><p style=" font-family: Open Sans, Helvetica Neue, Helvetica, Arial, sans-serif;color:#333;text-align: justify;line-height: 30px;">Olá, tudo bem? A partir de agora você faz parte da Academia Da Mente, nosso propósito é auxiliar você através da psicoeducação e o do desenvolvimento mental, aprimorar suas habilidades emocionais, estratégias e recursos pessoais para lidar e superar os problemas do dia a dia.</p></td></tr>
						<tr><td colspan="3" style="padding:30px 0;text-align:center;"><h1 style=" font-family: Open Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:25px;color:#58bbb1">Olha o que separamos para você!</h1></td></tr>
						<tr>
							<td width="33%">
								<a href="http://academia.clinicaecare.com.br/trilha/?id=145" style="border-radius:10px;background: #47657d;display: block;margin:5px;padding:30;text-align:center;text-decoration: none;height:150px;padding-top:100px; font-family: Open Sans, Helvetica Neue, Helvetica, Arial, sans-serif;">
									<h1 style="color:#fff;font-size: 20px;">Ansiedade</h1>
									<h2 style="color:#fff;font-size: 15px;font-weight: normal;">Por que sentimos ansiedade?</h2>
								</a>
							</td>
							<td width="33%">
								<a href="http://academia.clinicaecare.com.br/trilha/?id=142" style="border-radius:10px;background: #ff9800;display: block;margin:5px;padding:30;text-align:center;text-decoration: none;height:150px;padding-top:100px; font-family: Open Sans, Helvetica Neue, Helvetica, Arial, sans-serif;">
									<h1 style="color:#fff;font-size: 20px;">Burnout</h1>
									<h2 style="color:#fff;font-size: 15px;font-weight: normal;">Entenda o que o estresse pode provocar!</h2>
								</a>
							</td>
							<td width="33%">
							<a href="http://academia.clinicaecare.com.br/trilha/?id=143" style="border-radius:10px;background: #21346b;display: block;margin:5px;padding:30;text-align:center;text-decoration: none;height:150px;padding-top:100px; font-family: Open Sans, Helvetica Neue, Helvetica, Arial, sans-serif;">
								<h1 style="color:#fff;font-size: 20px;">Depressão</h1>
								<h2 style="color:#fff;font-size: 15px;font-weight: normal;">Quais são os sintomas?</h2>
							</a>
							</td>
						</tr>
						<tr>
							<td colspan="3" style="text-align:center;padding-top:50px;">
								<img src="http://academia.clinicaecare.com.br/wp-content/uploads/2020/11/video-files.png" width="70" alt="">
							</td>
						</tr>
						<tr>
							<td colspan="3" style="text-align: center;padding-top:10px;">
								<a href="http://academia.clinicaecare.com.br/como-usar-a-academia-da-mente/" style=" font-family: Open Sans, Helvetica Neue, Helvetica, Arial, sans-serif;color:#333;text-align: justify;line-height: 30px;"><b>Clique aqui para assistir nosso vídeo <br> de boas-vindas da Academia da Mente.</b></a>
							</td>
						</tr>
						<tr>
							<td colspan="3" style="text-align:center;padding-top:50px;">
								<img src="http://academia.clinicaecare.com.br/wp-content/uploads/2020/10/logo-footer.png" alt="">
							</td>
						</tr>
						<tr><td colspan="3" style="text-align:center;padding:20px 0 50px 0;"><a href="http://academia.clinicaecare.com.br/trilha/?id=143" style="text-decoration: none;color:#333;font-weight: bold;font-family: Open Sans, Helvetica Neue, Helvetica, Arial, sans-serif;">Academia da Mente</a></td></tr>
					</table>
				</body>
			</html>';

		    $mail->SMTPOptions = array(
			    'ssl' => array(
			        'verify_peer' => false,
			        'verify_peer_name' => false,
			        'allow_self_signed' => true
			    )
			);
		    $mail->send();
		    //echo 'Message has been sent';
		} catch (Exception $e) {
		    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}
	}