<?php
namespace Library\Models;
 
use \Library\Entities\News;
 
class NewsManager_PDO extends NewsManager
{
  
  const NOM_TABLE = 'nv_news';
   
  /**
   * @see NewsManager::add()
   */
  protected function add(\Library\Entities\News $news)
  {
    $requete = $this->dao->prepare('INSERT INTO ' . self::NOM_TABLE . ' SET auteur = :auteur, titre = :titre, contenu = :contenu, privee = :privee, dateAjout = NOW(), editeur = :auteur, 
									dateModif = NOW(), changement = 0, archive = :archive');
     
    $requete->bindValue(':titre', $news->titre());
    $requete->bindValue(':auteur', $news->auteurId());
    $requete->bindValue(':contenu', $news->contenu());
	$requete->bindValue(':privee', $news->privee(), \PDO::PARAM_BOOL);
	$requete->bindValue(':archive', $news->archive());
     
    $requete->execute();
  }
   
  /**
   * @see NewsManager::count()
   */
  public function count($privee = true)
  {
	if(!$privee)
		$sql = ' WHERE privee=0 ';
	else
		$sql = '';
		
    return $this->dao->query('SELECT COUNT(*) FROM ' . self::NOM_TABLE . $sql)->fetchColumn();
  }
   
  /**
   * @see NewsManager::delete()
   */
  public function delete($id)
  {
    $this->dao->exec('DELETE FROM ' . self::NOM_TABLE . '
					  WHERE id = '.(int) $id);
  }
   
  /**
   * @see NewsManager::getListe()
   */
  public function getListe($debut = -1, $limite = -1, $privee = false, \Library\Models\MembreManager $membreManager = null)
  {
    $sql = 'SELECT id, auteur, titre, contenu, dateAjout, dateModif, editeur, privee, archive, changement 
			FROM ' . self::NOM_TABLE; 

	if(!$privee)
		$sql .= ' WHERE privee=0 ';
		
	$sql .=	' ORDER BY dateAjout DESC';
							
    // On vérifie l'intégrité des paramètres fournis.
    if ($debut != -1 || $limite != -1)
    {
		$sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
    }
     
    $requete = $this->dao->query($sql);
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Library\Entities\News');
	
	$listeNews = $requete->fetchAll();
	
	foreach ($listeNews as $news)
    {
		$news->setDateAjout(new \DateTime($news->dateAjout()));
		$news->setDateModif(new \DateTime($news->dateModif()));
    }
	
	if($membreManager !== null)
	{
		foreach ($listeNews as $news)
		{
			$news->setAuteur($membreManager->getUnique($news->auteur()));
			if ($news->auteur()->id() == $news->editeur())
				$news->setEditeur($news->auteur());
			else
				$news->setEditeur($membreManager->getUnique($news->editeur()));
		}
	}
     
    $requete->closeCursor();
     
    return $listeNews;
  }
   
  /**
   * @see NewsManager::getUnique()
   */
  public function getUnique($id, \Library\Models\MembreManager $membreManager = null)
  {
    $requete = $this->dao->prepare('SELECT id, auteur, titre, contenu, dateAjout, dateModif, editeur, privee, archive, changement
									FROM ' . self::NOM_TABLE . ' 
									WHERE id = :id');
    $requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
    $requete->execute();
     
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\News');
	
	if ($news = $requete->fetch())
    {
      $news->setDateAjout(new \DateTime($news->dateAjout()));
      $news->setDateModif(new \DateTime($news->dateModif()));
	  
	  if($membreManager !== null)
	  {
		$news->setAuteur($membreManager->getUnique($news->auteur()));
		if ($news->auteur()->id() == $news->editeur())
			$news->setEditeur($news->auteur());
		else
			$news->setEditeur($membreManager->getUnique($news->editeur()));
	  }
     
       
      return $news;
    }
	
	return null;
  }
   
  /**
   * @see NewsManager::update()
   */
  protected function update(\Library\Entities\News $news)
  {
    $requete = $this->dao->prepare('UPDATE ' . self::NOM_TABLE . ' SET auteur = :auteur, titre = :titre, contenu = :contenu, privee = :privee, dateModif = NOW(), editeur = :editeur, archive = :archive, changement = :chngt, archive = :archive 
									WHERE id = :id');
     
    $requete->bindValue(':titre', $news->titre());
    $requete->bindValue(':auteur', $news->auteurId());
    $requete->bindValue(':contenu', $news->contenu());
	$requete->bindValue(':editeur', $news->editeurId());
	$requete->bindValue(':privee', $news->privee(), \PDO::PARAM_BOOL);
    $requete->bindValue(':id', $news->id(), \PDO::PARAM_INT);
	$requete->bindValue(':archive', $news->archive(), \PDO::PARAM_STR);
	$requete->bindValue(':chngt', $news->changement(), \PDO::PARAM_BOOL);
     
    $requete->execute();
  }
  
  /**
   * @see \Library\Models\NewsManager::archivage()
   */
  public function archivage()
  {
	  	$requete = $this->dao->prepare('UPDATE ' . self::NOM_TABLE . ' SET changement=1 ORDER BY dateAjout DESC LIMIT 1');
	  	return $requete->execute();
  }
}