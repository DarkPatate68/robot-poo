<?php
namespace Library\Models;
 
use \Library\Entities\MailContact;

class MailContactManager_PDO extends MailContactManager
{
  /**
   * Attribut contenant l'instance représentant la BDD.
   * @type PDO
   */
     
  const NOM_TABLE = 'nv_mail_contact'; /**< Nom de la table correspondant au manager */
   
    
  /**
   * @see MailContactManager::add()
   */
  public function add(\Library\Entities\MailContact $contact)
  {
    $requete = $this->dao->prepare('INSERT INTO ' . self::NOM_TABLE . ' 
									SET nom = :nom, 
										adresse = :adresse,
										commentaire = :com');
     
    $requete->bindValue(':nom', $contact->nom());
    $requete->bindValue(':adresse', $contact->adresse());
	$requete->bindValue(':com', $contact->commentaire());
     
    return $requete->execute();
  }
   
  /**
   * @see MailContactManager::count()
   */
  public function count()
  {
    return $this->dao->query('SELECT COUNT(*) FROM ' . self::NOM_TABLE . '')->fetchColumn();
  }
  
  /**
   * @see MailContactManager::delete()
   */
  public function delete($id)
  {
  	$id = (int) $id;
    $this->dao->exec('DELETE FROM ' . self::NOM_TABLE . ' 
					  WHERE id=' . (string) $id);
  }
    
     
  /**
   * @see MailContactManager::getListe()
   */
  public function getListe($debut = -1, $limite = -1, $initial = false)
  {
  	if(!is_string($initial))
  		$initial = false;
  	
    $sql = 'SELECT * 
			FROM ' . self::NOM_TABLE; 

	if($initial !== false)
		$sql .= ' WHERE nom LIKE \'' . $initial . '%\' ';
			
	$sql .=	' ORDER BY date DESC';
							
    // On vérifie l'intégrité des paramètres fournis.
    if ($debut != -1 || $limite != -1)
    {
		$sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
    }
     
    $requete = $this->dao->query($sql);
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Library\Entities\MailContact');
	
	$listeContact = $requete->fetchAll();
	
	$requete->closeCursor();
     
    return $listeContact;
  }
  
       
  /**
   * @see MailContactManager::getUnique()
   */
  public function getUnique($id)
  {
    $requete = $this->dao->prepare('SELECT *
									FROM ' . self::NOM_TABLE . ' 
									WHERE id = :id');
    $requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
    if($requete->execute() === false)
		return false;
     
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\MailContact');
	
	if ($contact = $requete->fetch())
    {
      return $contact;
    }
	return false;
  }
  
  /**
   * @see MailContactManager::update()
   */
  public function update(\Library\Entities\MailContact $contact)
  {
    $requete = $this->dao->prepare('UPDATE ' . self::NOM_TABLE . ' 
									SET nom = :nom, 
										adresse = :adresse,
										commentaire = :com 
									WHERE id = :id');
    
	$requete->bindValue(':id', $contact->id());
    $requete->bindValue(':nom', $contact->nom());
    $requete->bindValue(':adresse', $contact->adresse());
	$requete->bindValue(':com', $contact->commentaire());
     
    return $requete->execute();
  }  
}