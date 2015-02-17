<?php
	if(isset($_GET['fichier']))
		$fichier = (string) $_GET['fichier'];
	else
	{
		echo 'Il n\'y a aucun fichier &agrave; charger';
		exit;
	}
	
		
	if(!preg_match('#^[http|www]#', $fichier))
		$fichier = 'http://www2.insa-strasbourg.fr/club-robotique/Web/fichiers/' . $fichier;
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Creo View Express — CRIS</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<style>
		embed
		{
			border: 1px solid black;
		}
		
		html {
			height: 100%;
		}
		
		body {
			height: 100%;
			margin: 0;
			padding: 0;
			text-align: center;
		}
	</style>
  </head>
  <body>
    <embed height="99%" width="99%" name="plugin" src="<?php echo $fichier; ?>" type="application/x-pvlite9-ed">
  </body>
</html>
	