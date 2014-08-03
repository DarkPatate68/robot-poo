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
									SET id_membre = :membre, 
										description = :desc,
										fonction = :fonction,
										photo = :photo,
										archive = :archive');
     
    $requete->bindValue(':membre', $equipe->membre());
    $requete->bindValue(':desc', $equipe->description());
	$requete->bindValue(':fonction', $equipe->fonction());
	$requete->bindValue(':photo', $equipe->photo());
	$requete->bindValue(':archive', $equipe->archive());
     
    return $requete->execute();
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
    $this->dao->exec('DELETE FROM ' . self::NOM_TABLE . ' 
					  WHERE id = '.(int) $id);
  }
  
  
  
  /**
   * @see EquipeManager::deleteByMembreAndArchive()
   */
  public function deleteByMembreAndArchive($membre, $archive)
  {
	$requete = $this->dao->prepare('DELETE
									FROM ' . self::NOM_TABLE . ' 
									WHERE id_membre = :membre AND archive = :archive');
    $requete->bindValue(':membre', (int) $membre, \PDO::PARAM_INT);
    $requete->bindValue(':archive', (string) $archive, \PDO::PARAM_STR);
    $requete->execute();
  }
  
  /**
   * @see EquipeManager::deleteByUrlAndTitre()
   */
  public function deleteByUrlAndTitre($url, $titre)
  {
	$requete = $this->dao->prepare('DELETE
									FROM ' . self::NOM_TABLE . ' 
									WHERE url = :url AND titre = :titre');
    $requete->bindValue(':url', (string) $url, \PDO::PARAM_STR);
    $requete->bindValue(':titre', (string) $titre, \PDO::PARAM_STR);
    $requete->execute();
  }
   
  /**
   * @see EquipeManager::getListe()
   */
  public function getListe(\Library\Models\MembreManager $membreManager = null)
  {
    $sql = 'SELECT *
			FROM ' . self::NOM_TABLE . '  
			ORDER BY url, archive DESC';
     
    $requete = $this->dao->query($sql);
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Equipe');
     
    $listePage = $requete->fetchAll();
	
	foreach ($listePage as $equipe)
    {
		$equipe->setDateModif(new \DateTime($equipe->dateModif()));
		
	  if($membreManager !== null)
	  {
		$equipe->setEditeur($membreManager->getUnique($equipe->editeur()));
	  }
    }
     
    $requete->closeCursor();
     
    return $listePage;
  }
  
  /**
   * @see EquipeManager::getListeAnnees()
   */
  public function getListeAnnees($url)
  {
	$sql = 'SELECT archive
			FROM ' . self::NOM_TABLE . '
			WHERE url = :url
			ORDER BY archive DESC';
			
	$requete = $this->dao->prepare($sql);
    $requete->bindValue(':url', (string) $url, \PDO::PARAM_STR);
    
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
      $equipe->setDateModif(new \DateTime($equipe->dateModif()));
	  
	  if($membreManager !== null)
	  {
		$equipe->setEditeur($membreManager->getUnique($equipe->editeur()));
	  }
      
      return $equipe;
    }
	return false;
  }
  
  /**
   * @see EquipeManager::getUniqueByUrl()
   */
  public function getUniqueByUrl($url, \Library\Models\MembreManager $membreManager = null)
  {
    $requete = $this->dao->prepare('SELECT *
									FROM ' . self::NOM_TABLE . ' 
									WHERE url = :url');
    $requete->bindValue(':url', (string) $url, \PDO::PARAM_STR);
    if($requete->execute() === false)
		return false;
     
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Equipe');
	
	if ($equipe = $requete->fetch())
    {
      $equipe->setDateModif(new \DateTime($equipe->dateModif()));
	  
	  if($membreManager !== null)
	  {
		$equipe->setEditeur($membreManager->getUnique($equipe->editeur()));
	  }
      
      return $equipe;
    }
	return false;
  }
  
  /**
   * @see EquipeManager::getUniqueByUrlAndArchive()
   */
  public function getUniqueByUrlAndArchive($url, $archive, \Library\Models\MembreManager $membreManager = null)
  {
    $requete = $this->dao->prepare('SELECT *
									FROM ' . self::NOM_TABLE . ' 
									WHERE url = :url AND archive = :archive');
    $requete->bindValue(':url', (string) $url, \PDO::PARAM_STR);
	$requete->bindValue(':archive', (string) $archive, \PDO::PARAM_STR);
    if($requete->execute() === false)
		return false;
     
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Equipe');
	
	if ($equipe = $requete->fetch())
    {
      $equipe->setDateModif(new \DateTime($equipe->dateModif()));
	  
	  if($membreManager !== null)
	  {
		$equipe->setEditeur($membreManager->getUnique($equipe->editeur()));
	  }
      
      return $equipe;
    }
	return false;
  }
  
  /**
   * @see EquipeManager::getUniqueByUrlAndTitre()
   */
  public function getUniqueByUrlAndTitre($url, $titre, \Library\Models\MembreManager $membreManager = null)
  {
    $requete = $this->dao->prepare('SELECT *
									FROM ' . self::NOM_TABLE . ' 
									WHERE url = :url AND titre = :titre');
    $requete->bindValue(':url', (string) $url, \PDO::PARAM_STR);
	$requete->bindValue(':titre', (string) $titre, \PDO::PARAM_STR);
    if($requete->execute() === false)
		return false;
     
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Equipe');
	
	if ($equipe = $requete->fetch())
    {
      $equipe->setDateModif(new \DateTime($equipe->dateModif()));
	  
	  if($membreManager !== null)
	  {
		$equipe->setEditeur($membreManager->getUnique($equipe->editeur()));
	  }
      
      return $equipe;
    }
	return false;
  }
  
  /**
   * @see \Library\Models\EquipeManager::getId()
   */
  public function getId($url, $archive)
  {
      $requete = $this->dao->prepare('SELECT id
									FROM ' . self::NOM_TABLE . '
									WHERE url = :url AND archive = :archive');
      $requete->bindValue(':url', (string) $url, \PDO::PARAM_STR);
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
									SET titre = :titre, 
										texte = :texte,
										url = :url,
										dateModif = NOW(), 
										editeur = :editeur,
										archive = :archive
									WHERE id = :id');
    
	$requete->bindValue(':id', $equipe->id());
    $requete->bindValue(':titre', $equipe->titre());
    $requete->bindValue(':texte', $equipe->texte());
	$requete->bindValue(':url', $equipe->url());
	$requete->bindValue(':archive', $equipe->archive());
	$requete->bindValue(':editeur', $equipe->editeurId());
    
     
    return $requete->execute();
  }
  
  /**
   * @see EquipeManager::updateUrlAndTitre()
   */
  function updateUrlAndTitre($ancUrl, $nvUrl, $ancTitre, $nvTitre)
  {
	$requete = $this->dao->prepare('UPDATE ' . self::NOM_TABLE . ' 
									SET titre = :nvtitre, 
										url = :nvurl
									WHERE url = :ancurl AND titre = :anctitre');
    
	$requete->bindValue(':ancurl', (string) $ancUrl, \PDO::PARAM_STR);
	$requete->bindValue(':anctitre', (string) $ancTitre, \PDO::PARAM_STR);
	$requete->bindValue(':nvurl', (string) $nvUrl, \PDO::PARAM_STR);
	$requete->bindValue(':nvtitre', (string) $nvTitre, \PDO::PARAM_STR);
	
	return $requete->execute();   
  }
  
}