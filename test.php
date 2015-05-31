<?php

	$connection = ssh2_connect('www2.insa-strasbourg.fr');
	if($connection === false)
	{
		echo 'Erreur connexion';
		exit();
	}
	//ssh2_auth_password($connection, 'robot', 'MCogejfF');

	//$sftp = ssh2_sftp($connection);
	
	/*$idConnexion = ftp_connect('www2.insa-strasbourg.fr');
	if($idConnexion === false)
	{
		echo 'Le problème est ici !! Connexion avec le FTP';
		exit();
	}
	$login_result = ftp_login($idConnexion, 'robot', 'MCogejfF');
	//print_r(ftp_rawlist($idConnexion, ftp_pwd($idConnexion)));
	ftp_chdir($idConnexion, 'www/');
	ftp_chdir($idConnexion, 'membres');
	ftp_chdir($idConnexion, 'UpData');
	return $idConnexion;*/
