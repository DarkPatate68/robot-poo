<?php
namespace Library\Models;
 
use \Library\Entities\Groupe;

class GroupeManager_PDO extends GroupeManager
{
  /**
   * Attribut contenant l'instance représentant la BDD.
   * @type PDO
   */
 
  const NOM_TABLE = 'nv_groupe';
   
    
  /**
   * @see GroupeManager::add()
   */
  public function add($nom)
  {
    $requete = $this->dao->prepare('INSERT INTO ' . self::NOM_TABLE . ' 
									SET nom = :nom');
     
    $requete->bindValue(':nom', $nom);  
     
    $requete->execute();
  }
   
  /**
   * @see GroupeManager::count()
   */
  public function count()
  {
    return $this->dao->query('SELECT COUNT(*) FROM ' . self::NOM_TABLE . '')->fetchColumn();
  }
   
  /**
   * @see GroupeManager::delete()
   */
  public function delete($id)
  {
    $this->dao->exec('DELETE FROM ' . self::NOM_TABLE . ' 
					  WHERE id = '.(int) $id);
  }
   
  /**
   * @see GroupeManager::getListe()
   */
  public function getListe()
  {
    $sql = 'SELECT * FROM ' . self::NOM_TABLE . ' ORDER BY id';
     
    $requete = $this->dao->query($sql);
    /*$requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Groupe');
     
    $listeGroupe = $requete->fetchAll();*/
	
	while($donnees = $requete->fetch(\PDO::FETCH_ASSOC))
	{
		$listeGroupe[] = new Groupe($donnees);
	}
     
    $requete->closeCursor();
     
    return $listeGroupe;
  }
  
  /**
   * (non-PHPdoc)
   * @see \Library\Models\GroupeManager::getListeChamp()
   */
  public function getListeChamp()
  {
  	$sql = 'SHOW COLUMNS FROM ' . self::NOM_TABLE;
  	$requete = $this->dao->query($sql);
  	return $requete->fetchAll();
  }
   
  /**
   * @see GroupeManager::getUnique()
   */
  public function getUnique($id)
  {
    $requete = $this->dao->prepare('SELECT *
									FROM ' . self::NOM_TABLE . ' 
									WHERE id = :id');
    $requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
	try{
		$requete->execute();
	}
	catch (\Exception $e) // On va attraper les exceptions "Exception" s'il y en a une qui est levée
	{
	  return false;
	}
     
    //$requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Groupe');
	
	return new Groupe($requete->fetch(\PDO::FETCH_ASSOC));
  }
   
  /**
   * @see GroupeManager::update()
   */
  protected function update(Library\Entities\Groupe $groupe)
  {
    /*$requete = $this->dao->prepare('UPDATE ' . self::NOM_TABLE . ' 
									SET nom = :nom, 
										prenom = :prenom, 
										pseudo = :pseudo, 
										usuel = :usuel, 
										avatar = :avatar,
										section = :section,
										actif = :actif,
										valide = :valide,
										biographie = :biographie,
										courriel = :courriel,
										groupe = :groupe
									WHERE id = :id');
     
    $requete->bindValue(':nom', $groupe->nom());
    $requete->bindValue(':prenom', $groupe->prenom());
    $requete->bindValue(':pseudo', $groupe->pseudo());
	$requete->bindValue(':usuel', $groupe->usuel());
    $requete->bindValue(':avatar', $groupe->avatar());
    $requete->bindValue(':section', $groupe->section());
	$requete->bindValue(':actif', $groupe->actif());
    $requete->bindValue(':valide', $groupe->valide());
    $requete->bindValue(':biographie', $groupe->biographie());
	$requete->bindValue(':courriel', $groupe->courriel());
    $requete->bindValue(':groupe', $groupe->groupe());
     
    $requete->execute();*/
  }
  
  public function miseAJour($liste, $listeChamp)
  {
  	foreach($liste as $groupe)
  	{
  		$sql = 'UPDATE ' . self::NOM_TABLE . ' SET ';
  		  		
  		foreach($listeChamp as $champ)
  		{
  			$bool = ($groupe[$champ])?'1':'0';
  			$sql .= $champ . '=' . $bool . ', ';
  		}
  		if($groupe['id'] == '0' || $groupe['id'] == '1' || $groupe['id'] == '2')
  			$sql = substr($sql, 0, -2); // Supprime l'espace et la virgule du dernier élément
  		else
  			$sql .= 'nom=:nom';
  		
  		$sql .= ' WHERE id=:id';
  		
  		$requete = $this->dao->prepare($sql);
  		
  		$requete->bindValue(':id', $groupe['id'],  \PDO::PARAM_INT);
  		if($groupe['id'] != '0' && $groupe['id'] != '1' && $groupe['id'] != '2')
  			$requete->bindValue(':nom', $groupe['nom'],  \PDO::PARAM_STR);
  		
  		$requete->execute();
  	}
  }
  
  /**
   * (non-PHPdoc)
   * @see \Library\Models\GroupeManager::existe()
   */
  public function existe($id)
  {
  	$requete = $this->dao->prepare('SELECT *
									FROM ' . self::NOM_TABLE . '
									WHERE id = :id');
  	$requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
	$requete->execute();
	if($requete->fetch() !== false)
		return true;
	else
		return false;
  }
}