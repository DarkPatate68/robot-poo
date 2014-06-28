<?php
namespace Library\Models;
 
use \Library\Entities\PageFixe;

class PageFixeManager_PDO extends PageFixeManager
{
  /**
   * Attribut contenant l'instance représentant la BDD.
   * @type PDO
   */
 
  const NOM_TABLE = 'nv_page_fixe';
   
    
  /**
   * @see MembreManager::add()
   */
  public function add(\Library\Entities\PageFixe $page)
  {
    $requete = $this->dao->prepare('INSERT INTO ' . self::NOM_TABLE . ' 
									SET titre = :titre, 
										texte = :texte,
										url = :url,
										dateModif = NOW(), 
										editeur = :editeur');
     
    $requete->bindValue(':titre', $page->titre());
    $requete->bindValue(':texte', $page->texte());
	$requete->bindValue(':url', $page->url());
	$requete->bindValue(':editeur', $page->editeurId());
     
    return $requete->execute();
  }
   
  /**
   * @see MembreManager::count()
   */
  public function count()
  {
    return $this->dao->query('SELECT COUNT(*) FROM ' . self::NOM_TABLE . '')->fetchColumn();
  }
   
  /**
   * @see MembreManager::delete()
   */
  public function delete($id)
  {
    $this->dao->exec('DELETE FROM ' . self::NOM_TABLE . ' 
					  WHERE id = '.(int) $id);
  }
   
  /**
   * @see MembreManager::getListe()
   */
  public function getListe(\Library\Models\MembreManager $membreManager = null)
  {
    $sql = 'SELECT id, url, titre, texte, dateModif, editeur
			FROM ' . self::NOM_TABLE . '  
			ORDER BY id';
     
    $requete = $this->dao->query($sql);
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\PageFixe');
     
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
   * @see MembreManager::getUnique()
   */
  public function getUnique($id, \Library\Models\MembreManager $membreManager = null)
  {
    $requete = $this->dao->prepare('SELECT id, titre, url, texte, dateModif, editeur
									FROM ' . self::NOM_TABLE . ' 
									WHERE id = :id');
    $requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
    if($requete->execute() === false)
		return false;
     
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\PageFixe');
	
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
   * @see MembreManager::getUniqueByUrl()
   */
  public function getUniqueByUrl($url, \Library\Models\MembreManager $membreManager = null)
  {
    $requete = $this->dao->prepare('SELECT id, titre, url, texte, dateModif, editeur
									FROM ' . self::NOM_TABLE . ' 
									WHERE url = :url');
    $requete->bindValue(':url', (string) $url, \PDO::PARAM_STR);
    if($requete->execute() === false)
		return false;
     
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\PageFixe');
	
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
   * @see MembreManager::update()
   */
  public function update(\Library\Entities\PageFixe $page)
  {
    $requete = $this->dao->prepare('UPDATE ' . self::NOM_TABLE . ' 
									SET titre = :titre, 
										texte = :texte,
										url = :url,
										dateModif = NOW(), 
										editeur = :editeur 
									WHERE id = :id');
    
	$requete->bindValue(':id', $page->id());
    $requete->bindValue(':titre', $page->titre());
    $requete->bindValue(':texte', $page->texte());
	$requete->bindValue(':url', $page->url());
	$requete->bindValue(':editeur', $page->editeurId());
    
     
    return $requete->execute();
  }
  
}