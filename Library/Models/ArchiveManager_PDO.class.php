<?php
namespace Library\Models;
 
class ArchiveManager_PDO extends \Library\Models\ArchiveManager
{
  
  const NOM_TABLE = 'nv_archive';
   
  /**
   * @see NewsManager::add()
   */
  public function add($annee)
  {
    $requete = $this->dao->prepare('INSERT INTO ' . self::NOM_TABLE . ' SET anneeScolaire = :date, timestamp = NOW()');
     
    $requete->bindValue(':date', $annee, \PDO::PARAM_STR);
     
    $requete->execute();
  }
   
  /**
   * @see NewsManager::count()
   */
  public function count($privee = true)
  {
	return $this->dao->query('SELECT COUNT(*) FROM ' . self::NOM_TABLE)->fetchColumn();
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
  public function getListe()
  {
    $sql = 'SELECT id, anneeScolaire, UNIX_TIMESTAMP(timestamp) AS timestamp, timestamp AS timeMySql
			FROM ' . self::NOM_TABLE . 
    		' ORDER BY anneeScolaire'; 
     
    $requete = $this->dao->query($sql);
    	
	return $requete->fetchAll();
  }
   
  /**
   * @see NewsManager::getUnique()
   */
  public function getUnique($id = -1)
  {
	if($id >= 0)
		$requete = $this->dao->prepare('SELECT id, anneeScolaire, timestamp
										FROM ' . self::NOM_TABLE . ' 
										WHERE id = '.(int) $id);
	else
		$requete = $this->dao->prepare('SELECT id, anneeScolaire, timestamp
										FROM ' . self::NOM_TABLE . '  
										ORDER BY id DESC');
	
    $requete->execute();
     
    return $requete->fetch();
  }
}