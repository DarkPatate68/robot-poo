<?php
namespace Library\Models;
 
use \Library\Entities\Partenaire;

class PartenaireManager_PDO extends PartenaireManager
{
  /**
   * Attribut contenant l'instance représentant la BDD.
   * @type PDO
   */
 
  const NOM_TABLE = 'nv_partenaire';
   
    
  /**
   * @see MembreManager::add()
   */
  public function add(\Library\Entities\Partenaire $partenaire)
  {
    $requete = $this->dao->prepare('INSERT INTO ' . self::NOM_TABLE . ' 
									SET nom = :nom, 
										description = :description,
										dateAjout = NOW(),
										dateModif = NOW(), 
										image = :image');
     
    $requete->bindValue(':nom', $partenaire->nom());
    $requete->bindValue(':description', $partenaire->description());
	$requete->bindValue(':image', $partenaire->image());
     
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
  public function getListe()
  {
    $sql = 'SELECT id, dateAjout, nom, description, dateModif, image
			FROM ' . self::NOM_TABLE . '  
			ORDER BY nom';
     
    $requete = $this->dao->query($sql);
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Partenaire');
     
    $listePage = $requete->fetchAll();
	
	foreach ($listePage as $partenaire)
    {
		$partenaire->setDateModif(new \DateTime($partenaire->dateModif()));
		$partenaire->setDateAjout(new \DateTime($partenaire->dateAjout()));
    }
     
    $requete->closeCursor();
     
    return $listePage;
  }
  
     
  /**
   * @see MembreManager::getUnique()
   */
  public function getUnique($id)
  {
    $requete = $this->dao->prepare('SELECT id, nom, dateAjout, description, dateModif, image
									FROM ' . self::NOM_TABLE . ' 
									WHERE id = :id');
    $requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
    if($requete->execute() === false)
		return false;
     
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Partenaire');
	
	if ($partenaire = $requete->fetch())
    {
      $partenaire->setDateModif(new \DateTime($partenaire->dateModif()));
	  $partenaire->setDateAjout(new \DateTime($partenaire->dateAjout()));      
      return $partenaire;
    }
	return false;
  }
   
  /**
   * @see MembreManager::update()
   */
  public function update(\Library\Entities\Partenaire $partenaire)
  {
    $requete = $this->dao->prepare('UPDATE ' . self::NOM_TABLE . ' 
									SET nom = :nom, 
										description = :description,
										dateModif = NOW(), 
										image = :image 
									WHERE id = :id');
    
	$requete->bindValue(':id', $partenaire->id());
    $requete->bindValue(':nom', $partenaire->nom());
    $requete->bindValue(':description', $partenaire->description());
	//$requete->bindValue(':dateAjout', $partenaire->dateAjout());
	$requete->bindValue(':image', $partenaire->image());
    
     
    return $requete->execute();
  }
  
}