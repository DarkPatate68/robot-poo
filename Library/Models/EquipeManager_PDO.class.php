<?php
namespace Library\Models;
 
use \Library\Entities\Equipe;

class EquipeManager_PDO extends EquipeManager
{
  /**
   * Attribut contenant l'instance représentant la BDD.
   * @type PDO
   */
     
  const NOM_TABLE = 'nv_equipe'; /**< Nom de la table correspondant au manager */
   
    
  /**
   * @see EquipeManager::add()
   */
  public function add(\Library\Entities\Equipe $equipe)
  {
    $requete = $this->dao->prepare('INSERT INTO ' . self::NOM_TABLE . ' 
									SET membre = :membre, 
										description = :desc,
										fonction = :fonction,
    									classe = :classe,
										photo = :photo,
										archive = :archive');
     
    $requete->bindValue(':membre', $equipe->membre()->id());
    $requete->bindValue(':desc', $equipe->description());
	$requete->bindValue(':fonction', $equipe->fonction());
	$requete->bindValue(':classe', $equipe->classe());
	$requete->bindValue(':photo', $equipe->photo());
	$requete->bindValue(':archive', $equipe->archive());
     
    $requete->execute();
    return $this->dao->lastInsertId();
  }
   
  /**
   * @see EquipeManager::count()
   */
  public function count()
  {
    return $this->dao->query('SELECT COUNT(*) FROM ' . self::NOM_TABLE . '')->fetchColumn();
  }
  
  /**
   * @see EquipeManager::countAnnee()
   */
  public function countAnnee($annee)
  {
	$requete = $this->dao->prepare('SELECT COUNT(*) FROM ' . self::NOM_TABLE . ' 
									WHERE archive = :archive');
    $requete->bindValue(':archive', (string) $annee, \PDO::PARAM_STR);
    $requete->execute();
    return $requete->fetchColumn();
  }
  
  
   
  /**
   * @see EquipeManager::delete()
   */
  public function delete($id)
  {
    $requete = $this->dao->prepare('DELETE
									FROM ' . self::NOM_TABLE . '
									WHERE id = :id');
    $requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
    return $requete->execute();
  }
  
  
  
  /**
   * @see EquipeManager::deleteByMembreAndArchive()
   */
  public function deleteByMembreAndArchive($membre, $archive)
  {
	$requete = $this->dao->prepare('DELETE
									FROM ' . self::NOM_TABLE . ' 
									WHERE membre = :membre AND archive = :archive');
    $requete->bindValue(':membre', (int) $membre, \PDO::PARAM_INT);
    $requete->bindValue(':archive', (string) $archive, \PDO::PARAM_STR);
    return $requete->execute();
  }
  
     
  /**
   * @see EquipeManager::getListe()
   */
  public function getListe(\Library\Models\MembreManager $membreManager = null)
  {
    $sql = 'SELECT *
			FROM ' . self::NOM_TABLE . '  
			ORDER BY membre, archive DESC';
     
    $requete = $this->dao->query($sql);
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Equipe');
     
    $listeEquipe = $requete->fetchAll();
	
	foreach ($listeEquipe as $equipe)
    {		
	  if($membreManager !== null)
	  {
		$equipe->setMembre($membreManager->getUnique($equipe->membre()));
	  }
    }
     
    $requete->closeCursor();
     
    return $listeEquipe;
  }
  
  /**
   * @see EquipeManager::getListeByArchive()
   */
  public function getListeByArchive($archive, \Library\Models\MembreManager $membreManager = null)
  {
	$sql = 'SELECT *
			FROM ' . self::NOM_TABLE . '
			WHERE archive = :archive
			ORDER BY fonction';
			
	$requete = $this->dao->prepare($sql);
    $requete->bindValue(':archive', (string) $archive, \PDO::PARAM_STR);
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Equipe');
    
	if($requete->execute() === false)
		return false;    
    
	$listeEquipe = $requete->fetchAll();
	
	foreach ($listeEquipe as $equipe)
	{
		if($membreManager !== null)
		{
			$equipe->setMembre($membreManager->getUnique($equipe->membre()));
		}
	}
	 
	$requete->closeCursor();
	 
	return $listeEquipe;
  }
  
  /**
   * @see EquipeManager::getListeAnnees()
   */
  public function getListeAnnees()
  {
  	$sql = 'SELECT DISTINCT archive
			FROM ' . self::NOM_TABLE .
  			' ORDER BY archive DESC';
  		
  	$requete = $this->dao->prepare($sql);
  	  
  	if($requete->execute() === false)
  		return false;
  
  	return $requete->fetchAll();
  }
  
     
  /**
   * @see EquipeManager::getUnique()
   */
  public function getUnique($id, \Library\Models\MembreManager $membreManager = null)
  {
    $requete = $this->dao->prepare('SELECT *
									FROM ' . self::NOM_TABLE . ' 
									WHERE id = :id');
    $requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
    if($requete->execute() === false)
		return false;
     
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Equipe');
	
	if ($equipe = $requete->fetch())
    {
      if($membreManager !== null)
	  {
		$equipe->setMembre($membreManager->getUnique($equipe->membre()));
	  }
      
      return $equipe;
    }
	return false;
  }
  
    
  /**
   * @see EquipeManager::getUniqueByMembreAndArchive()
   */
  public function getUniqueByMembreAndArchive($membre, $archive, \Library\Models\MembreManager $membreManager = null)
  {
    $requete = $this->dao->prepare('SELECT *
									FROM ' . self::NOM_TABLE . ' 
									WHERE membre = :membre AND archive = :archive');
    $requete->bindValue(':membre', (int) $membre, \PDO::PARAM_INT);
	$requete->bindValue(':archive', (string) $archive, \PDO::PARAM_STR);
    if($requete->execute() === false)
		return false;
     
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Equipe');
	
	if ($equipe = $requete->fetch())
    {
      if($membreManager !== null)
	  {
		$equipe->setMembre($membreManager->getUnique($equipe->membre()));
	  }
      
      return $equipe;
    }
	return false;
  }
  
  
  /**
   * @see \Library\Models\EquipeManager::getId()
   */
  public function getId($membre, $archive)
  {
      $requete = $this->dao->prepare('SELECT id
									FROM ' . self::NOM_TABLE . '
									WHERE membre = :membre AND archive = :archive');
      $requete->bindValue(':membre', (int) $membre, \PDO::PARAM_INT);
      $requete->bindValue(':archive', (string) $archive, \PDO::PARAM_STR);
      if($requete->execute() === false)
          return false;
      else
          return $requete->fetch()[0];
  }
   
  /**
   * @see EquipeManager::update()
   */
  public function update(\Library\Entities\Equipe $equipe)
  {
    $requete = $this->dao->prepare('UPDATE ' . self::NOM_TABLE . ' 
									SET archive = :archive, 
										membre = :membre,
										fonction = :fonction,
    									classe = :classe,
										description = :desc,
										photo = :photo
									WHERE id = :id');
    
	$requete->bindValue(':id', $equipe->id());
    $requete->bindValue(':membre', $equipe->membre()->id());
    $requete->bindValue(':fonction', $equipe->fonction());
    $requete->bindValue(':classe', $equipe->classe());
	$requete->bindValue(':desc', $equipe->description());
	$requete->bindValue(':archive', $equipe->archive());
	$requete->bindValue(':photo', $equipe->photo());
    
     
    return $requete->execute();
  }  
  
  /**
   * @see EquipeManager::updatePhoto()
   */
  public function updatePhoto($id, $photo)
  {
  	$requete = $this->dao->prepare('UPDATE ' . self::NOM_TABLE . '
									SET photo = :photo
									WHERE id = :id');
  
  	$requete->bindValue(':id', (int) $id);
  	$requete->bindValue(':photo', (string) $photo);
  
  	 
  	return $requete->execute();
  }
}