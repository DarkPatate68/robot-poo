<?php
namespace Library\Models;
 
use \Library\Entities\PageArchivable;

class PageArchivableManager_PDO extends PageArchivableManager
{
  /**
   * Attribut contenant l'instance représentant la BDD.
   * @type PDO
   */
     
  const NOM_TABLE = 'nv_page_archivable'; /**< Nom de la table correspondant au manager */
   
    
  /**
   * @see PageArchivableManager::add()
   */
  public function add(\Library\Entities\PageArchivable $page)
  {
    $requete = $this->dao->prepare('INSERT INTO ' . self::NOM_TABLE . ' 
									SET titre = :titre, 
										texte = :texte,
										url = :url,
										dateModif = NOW(), 
										editeur = :editeur,
										archive = :archive');
     
    $requete->bindValue(':titre', $page->titre());
    $requete->bindValue(':texte', $page->texte());
	$requete->bindValue(':url', $page->url());
	$requete->bindValue(':editeur', $page->editeurId());
	$requete->bindValue(':archive', $page->archive());
     
    return $requete->execute();
  }
   
  /**
   * @see PageArchivableManager::count()
   */
  public function count()
  {
    return $this->dao->query('SELECT COUNT(*) FROM ' . self::NOM_TABLE . '')->fetchColumn();
  }
  
  /**
   * @see PageArchivableManager::countUrl()
   */
  public function countUrl($url)
  {
	$requete = $this->dao->prepare('SELECT COUNT(*) FROM ' . self::NOM_TABLE . ' 
									WHERE url = :url');
    $requete->bindValue(':url', (string) $url, \PDO::PARAM_STR);
    $requete->execute();
    return $requete->fetchColumn();
  }
   
  /**
   * @see PageArchivableManager::delete()
   */
  public function delete($id)
  {
    $this->dao->exec('DELETE FROM ' . self::NOM_TABLE . ' 
					  WHERE id = '.(int) $id);
  }
  
  /**
   * @see PageArchivableManager::deleteByUrl()
   */
  public function deleteByUrl($url)
  {
	$requete = $this->dao->prepare('DELETE
									FROM ' . self::NOM_TABLE . ' 
									WHERE url = :url');
    $requete->bindValue(':url', (string) $url, \PDO::PARAM_STR);
    $requete->execute();
  }
  
  /**
   * @see PageArchivableManager::deleteByUrlAndArchive()
   */
  public function deleteByUrlAndArchive($url, $archive)
  {
	$requete = $this->dao->prepare('DELETE
									FROM ' . self::NOM_TABLE . ' 
									WHERE url = :url AND archive = :archive');
    $requete->bindValue(':url', (string) $url, \PDO::PARAM_STR);
    $requete->bindValue(':archive', (string) $archive, \PDO::PARAM_STR);
    $requete->execute();
  }
  
  /**
   * @see PageArchivableManager::deleteByUrlAndTitre()
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
   * @see PageArchivableManager::getListe()
   */
  public function getListe(\Library\Models\MembreManager $membreManager = null)
  {
    $sql = 'SELECT *
			FROM ' . self::NOM_TABLE . '  
			ORDER BY url, archive DESC';
     
    $requete = $this->dao->query($sql);
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\PageArchivable');
     
    $listePage = $requete->fetchAll();
	
	foreach ($listePage as $page)
    {
		$page->setDateModif(new \DateTime($page->dateModif()));
		
	  if($membreManager !== null)
	  {
		$page->setEditeur($membreManager->getUnique($page->editeur()));
	  }
    }
     
    $requete->closeCursor();
     
    return $listePage;
  }
  
  /**
   * @see PageArchivableManager::getListeAnnees()
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
   * @see PageArchivableManager::getUnique()
   */
  public function getUnique($id, \Library\Models\MembreManager $membreManager = null)
  {
    $requete = $this->dao->prepare('SELECT *
									FROM ' . self::NOM_TABLE . ' 
									WHERE id = :id');
    $requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
    if($requete->execute() === false)
		return false;
     
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\PageArchivable');
	
	if ($page = $requete->fetch())
    {
      $page->setDateModif(new \DateTime($page->dateModif()));
	  
	  if($membreManager !== null)
	  {
		$page->setEditeur($membreManager->getUnique($page->editeur()));
	  }
      
      return $page;
    }
	return false;
  }
  
  /**
   * @see PageArchivableManager::getUniqueByUrl()
   */
  public function getUniqueByUrl($url, \Library\Models\MembreManager $membreManager = null)
  {
    $requete = $this->dao->prepare('SELECT *
									FROM ' . self::NOM_TABLE . ' 
									WHERE url = :url');
    $requete->bindValue(':url', (string) $url, \PDO::PARAM_STR);
    if($requete->execute() === false)
		return false;
     
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\PageArchivable');
	
	if ($page = $requete->fetch())
    {
      $page->setDateModif(new \DateTime($page->dateModif()));
	  
	  if($membreManager !== null)
	  {
		$page->setEditeur($membreManager->getUnique($page->editeur()));
	  }
      
      return $page;
    }
	return false;
  }
  
  /**
   * @see PageArchivableManager::getUniqueByUrlAndArchive()
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
     
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\PageArchivable');
	
	if ($page = $requete->fetch())
    {
      $page->setDateModif(new \DateTime($page->dateModif()));
	  
	  if($membreManager !== null)
	  {
		$page->setEditeur($membreManager->getUnique($page->editeur()));
	  }
      
      return $page;
    }
	return false;
  }
  
  /**
   * @see PageArchivableManager::getUniqueByUrlAndTitre()
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
     
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\PageArchivable');
	
	if ($page = $requete->fetch())
    {
      $page->setDateModif(new \DateTime($page->dateModif()));
	  
	  if($membreManager !== null)
	  {
		$page->setEditeur($membreManager->getUnique($page->editeur()));
	  }
      
      return $page;
    }
	return false;
  }
  
  /**
   * @see \Library\Models\PageArchivableManager::getId()
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
   * @see PageArchivableManager::update()
   */
  public function update(\Library\Entities\PageArchivable $page)
  {
    $requete = $this->dao->prepare('UPDATE ' . self::NOM_TABLE . ' 
									SET titre = :titre, 
										texte = :texte,
										url = :url,
										dateModif = NOW(), 
										editeur = :editeur,
										archive = :archive
									WHERE id = :id');
    
	$requete->bindValue(':id', $page->id());
    $requete->bindValue(':titre', $page->titre());
    $requete->bindValue(':texte', $page->texte());
	$requete->bindValue(':url', $page->url());
	$requete->bindValue(':archive', $page->archive());
	$requete->bindValue(':editeur', $page->editeurId());
    
     
    return $requete->execute();
  }
  
  /**
   * @see PageArchivableManager::updateUrlAndTitre()
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