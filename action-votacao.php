<?php 

	include_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );

	$action = (!empty($_POST['action']) && isset($_POST['action'])) ? $_POST['action'] : 0;
	$content = (!empty($_POST['offset']) && isset($_POST['offset'])) ? $_POST['offset'] : 0;

	switch ($action) {
		case 'votar':
			votar($content);
			break;
		case 'getVotacao':
			getVotacao();
			break;
		default:
			# code...
			break;
	}

	function votar($votacao) {

		global $wpdb;

		$select = "select count(id) votou from wp_votacao where id_user = '{$votacao['id_user']}' and id_conteudo = '{$votacao['id_conteudo']}'";
		$consulta = $wpdb->get_results($select);

		if($consulta[0]->votou != '0') {
			echo 'jaVotou';
			exit;
		}

		$query = "insert into wp_votacao (id_user, id_conteudo, estrelas) values ('{$votacao['id_user']}', '{$votacao['id_conteudo']}', '{$votacao['estrelas']}')";
		$retorno = $wpdb->query($query);

		echo $retorno;
	}

	function getVotacao($votacao) {
		global $wpdb;

	  	// $select = "select id from conteudo_concluido where id_user = '{$_POST['offset']['id_user']}' and id_conteudo = '{$_POST['offset']['id_conteudo']}'";
	  	$select = "select estrelas from wp_votacao where id_user = '{$votacao['id_user']}' and id_conteudo = '{$votacao['id_conteudo']}'";
		$consulta = $wpdb->get_results($select);

		if($consulta)
			echo json_encode($consulta);
		else 
			echo json_encode(false);

	}