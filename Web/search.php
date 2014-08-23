<?php
require '../Library/autoload.inc.php';
if(!isset($_GET['s']))
	exit;
 
$bdd = Library\PDOFactory::getMysqlConnection();

	if(isset($_GET['archive']))
	{
		$sql2 = 'SELECT pseudo
				FROM nv_membre
				WHERE NOT EXISTS (SELECT NULL FROM nv_equipe WHERE nv_membre.id = nv_equipe.membre AND nv_equipe.archive = :archive)';

		$requete = $bdd->prepare($sql2);
		$requete->bindValue(':archive', str_ireplace('-', '/', (string) $_GET['archive']), \PDO::PARAM_STR);
	}
	else
	{
		$sql = 'SELECT pseudo
			FROM nv_membre
			ORDER BY id';
		$requete = $bdd->prepare($sql);  	
	} 
	
	$requete->execute();
  	$listeMembre = $requete->fetchAll();
  
  	$requete->closeCursor();
  	 
	foreach($listeMembre as $membre)
	{
		if($membre['pseudo'] != 'Anonyme')
			$listeMembre2[] = $membre['pseudo'];
	}
		
	 $results = array(); // Le tableau où seront stockés les résultats de la recherche
	 $tailleListeMbr2 = count($listeMembre2);

    // La boucle ci-dessous parcourt tout le tableau $listeMembre2, jusqu'à un maximum de 10 résultats

        for ($i = 0 ; $i < $tailleListeMbr2 && count($results) < 10 ; $i++) {
            if (stripos($listeMembre2[$i], $_GET['s']) === 0) { // Si la valeur commence par les mêmes caractères que la recherche
          
                array_push($results, $listeMembre2[$i]); // On ajoute alors le résultat à la liste à retourner
            
            }
        }
	 
  	echo implode('|', $results);
