<?php
namespace Library\Models;
 
use \Library\Entities\Membre;

class MembreManager_PDO extends MembreManager
{
  /**
   * Attribut contenant l'instance représentant la BDD.
   * @type PDO
   */
 
  const NOM_TABLE = 'nv_membre';
   
    
  /**
   * @see MembreManager::add()
   */
  public function add(\Library\Entities\Membre $membre)
  {
    $requete = $this->dao->prepare('INSERT INTO ' . self::NOM_TABLE . ' 
									SET nom = :nom, 
										prenom = :prenom, 
										pseudo = :pseudo, 
										usuel = :usuel, 
										avatar = :avatar,
										section = :section,
										actif = :actif,
										valide = :valide,
										biographie = :biographie,
										dateInscription = NOW(),
										courriel = :courriel,
										groupe = :groupe,
										tshirt = :tshirt,
										telephone = :tel,
										mdp = :mdp');
     
    $requete->bindValue(':nom', $membre->nom());
    $requete->bindValue(':prenom', $membre->prenom());
    $requete->bindValue(':pseudo', $membre->pseudo());
	$requete->bindValue(':usuel', 'USUEL_PSEUDO');
	$requete->bindValue(':tshirt', 'NON DÉFINI');
    $requete->bindValue(':avatar', $membre->avatar());
    $requete->bindValue(':section', $membre->section());
	$requete->bindValue(':actif', $membre->actif());
    $requete->bindValue(':valide', $membre->valide());
    $requete->bindValue(':biographie', $membre->biographie());
	$requete->bindValue(':courriel', $membre->courriel());
    $requete->bindValue(':groupe', $membre->groupe());
    $requete->bindValue(':tel', '');
	$requete->bindValue(':mdp', $membre->mdp());
     
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
  public function getListe(\Library\Models\GroupeManager $groupeManager = null)
  {
    $sql = 'SELECT *
			FROM ' . self::NOM_TABLE . '  
			ORDER BY id';
     
    $requete = $this->dao->query($sql);
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Membre');
     
    $listeMembre = $requete->fetchAll();
	
	foreach ($listeMembre as $membre)
    {
		$membre->setDateInscription(new \DateTime($membre->dateInscription()));
		$membre->setCourriel($membre->courriel());
		$membre->setUsuel($membre->usuel());
		
		if($groupeManager !== null)
		{
			$membre->setGroupeObjet($groupeManager->getUnique($membre->groupe()));
		}
    }
     
    $requete->closeCursor();
     
    return $listeMembre;
  }
  
  /**
   * @see MembreManager::getListeMinimale()
   */
  public function getListeMinimale()
  {
  	$sql = 'SELECT id, nom, prenom, pseudo
			FROM ' . self::NOM_TABLE . '
			ORDER BY id';
  	$requete = $this->dao->prepare($sql);
  	$requete->execute();
  	$requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Membre');
  	 
  	$listeMembre = $requete->fetchAll();
  
  	$requete->closeCursor();
  	 
  	return $listeMembre;
  }
  
  /**
   * @see MembreManager::connexion()
   */
  public function connexion($pseudo, $mdp)
  {
	$requete = $this->dao->prepare('SELECT *
									FROM ' . self::NOM_TABLE . ' 
									WHERE pseudo = :pseudo AND mdp = :mdp');
    $requete->bindValue(':pseudo', (string) $pseudo, \PDO::PARAM_STR);
	$requete->bindValue(':mdp', (string) $mdp, \PDO::PARAM_STR);
    $requete->execute();
     
    if(!$requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Membre'))
		return false;
	
	if($membre = $requete->fetch())
    {
      $membre->setDateInscription(new \DateTime($membre->dateInscription()));
      $membre->setCourriel($membre->courriel());
	  $membre->setUsuel($membre->usuel());

      return $membre;
    }
  }
   
  /**
   * @see MembreManager::getUnique()
   */
  public function getUnique($id, \Library\Models\GroupeManager $groupeManager = null)
  {
    $requete = $this->dao->prepare('SELECT *
									FROM ' . self::NOM_TABLE . ' 
									WHERE id = :id');
    $requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
    $requete->execute();
     
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Membre');
	
	if ($membre = $requete->fetch())
    {
      $membre->setDateInscription(new \DateTime($membre->dateInscription()));
      $membre->setCourriel($membre->courriel());
	  //$membre->setUsuel($membre->usuel());
    
      if($groupeManager !== null)
      {
          $membre->setGroupeObjet($groupeManager->getUnique($membre->groupe()));
      }
       
      return $membre;
    }
    
    return false;
  }
  
  /**
   * @see MembreManager::getUniqueByPseudo()
   */
  public function getUniqueByPseudo($pseudo, \Library\Models\GroupeManager $groupeManager = null)
  {
  	$requete = $this->dao->prepare('SELECT *
									FROM ' . self::NOM_TABLE . '
									WHERE pseudo = :pseudo');
  	$requete->bindValue(':pseudo', (string) $pseudo, \PDO::PARAM_STR);
  	$requete->execute();
  	 
  	$requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Membre');
  
  	if ($membre = $requete->fetch())
  	{
  		$membre->setDateInscription(new \DateTime($membre->dateInscription()));
  		$membre->setCourriel($membre->courriel());
  		//$membre->setUsuel($membre->usuel());
  
  		if($groupeManager !== null)
  		{
  			$membre->setGroupeObjet($groupeManager->getUnique($membre->groupe()));
  		}
  		 
  		return $membre;
  	}
  	
  	return false;
  }
  
  /**
   * (non-PHPdoc)
   * @see \Library\Models\MembreManager::getId()
   */
  public function getId($pseudo)
  {
  	$requete = $this->dao->prepare('SELECT id
									FROM ' . self::NOM_TABLE . '
									WHERE pseudo = :pseudo');
  	$requete->bindValue(':pseudo', (string) $pseudo, \PDO::PARAM_STR);
  	$requete->execute();
  	return $requete->fetch()[0];
  }
   
  /**
   * @see MembreManager::update()
   */
  public function update(\Library\Entities\Membre $membre, $mdp = false)
  {
	$mdp = (bool) $mdp;
	if($mdp)
		$mdpTxt = ', mdp = :mdp';
	else
		$mdpTxt = '';
    $requete = $this->dao->prepare('UPDATE ' . self::NOM_TABLE . ' 
									SET nom = :nom, 
										prenom = :prenom, 
										usuel = :usuel, 
										avatar = :avatar,
										section = :section,
										actif = :actif,
										valide = :valide,
										biographie = :biographie,
										courriel = :courriel,
										groupe = :groupe,
										tshirt = :tshirt,
										telephone = :tel'.
										$mdpTxt
									.' WHERE id = :id');
     
    $requete->bindValue(':nom', $membre->nom());
    $requete->bindValue(':prenom', $membre->prenom());
    //$requete->bindValue(':pseudo', $membre->pseudo());
	$requete->bindValue(':usuel', $membre->usuelCode());
    $requete->bindValue(':avatar', $membre->avatar());
    $requete->bindValue(':section', $membre->section());
	$requete->bindValue(':actif', $membre->actif());
    $requete->bindValue(':valide', $membre->valide());
    $requete->bindValue(':biographie', $membre->biographie());
	$requete->bindValue(':courriel', $membre->courriel());
    $requete->bindValue(':groupe', $membre->groupe());
    $requete->bindValue(':tshirt', $membre->tshirt());
    $requete->bindValue(':tel', $membre->telephone());
	if($mdp)
		$requete->bindValue(':mdp', $membre->mdp());
	$requete->bindValue(':id', $membre->id());
     
    return $requete->execute();
  }
  
  public function courrielExistant($courriel)
  {
	$requete = $this->dao->prepare('SELECT id
									FROM ' . self::NOM_TABLE . ' 
									WHERE courriel = :courriel');
    $requete->bindValue(':courriel', (string) $courriel, \PDO::PARAM_STR);
    $requete->execute();
	
	if($requete->fetch())
		return true;
	else
		return false;
	
  }
  
  /**
   * @see MembreManager::getNotValidate()
   */
  public function getNotValidate()
  {
	$requete = $this->dao->prepare('SELECT id, nom, prenom, pseudo, usuel, avatar, section, actif, valide, biographie, dateInscription, courriel, groupe
									FROM ' . self::NOM_TABLE . ' 
									WHERE valide = 0 AND id != 0');
    $requete->execute();
     
    if(!$requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Membre'))
		return false;
	
	if($membres = $requete->fetchAll())
    {
		foreach($membres as $membre)
		{
			$membre->setDateInscription(new \DateTime($membre->dateInscription()));
		}
       return $membres;
	}
  }
  
  /**
   * @see MembreManager::valider()
   */
  public function valider(array $liste)
  {
	foreach($liste as $donnee)
    {
    	$requete = $this->dao->prepare('
					            UPDATE ' . self::NOM_TABLE . ' 
					            SET
					                valide = :valide,
					                actif = :actif,
					                groupe = :groupe
					            WHERE id = :id');
    	
    	$requete->bindValue(':valide', (int) $donnee['valide'], \PDO::PARAM_INT);
    	$requete->bindValue(':actif', (int) $donnee['actif'], \PDO::PARAM_INT);
    	$requete->bindValue(':groupe', (int) $donnee['groupe'], \PDO::PARAM_INT);
    	$requete->bindValue(':id', (int) $donnee['id'], \PDO::PARAM_INT);
    	$requete->execute();
    }
  }
  
  /**
   * @see MembreManager::getMdp()
   */
  public function getMdp($id)
  {
	$requete = $this->dao->prepare('SELECT mdp
									FROM ' . self::NOM_TABLE . ' 
									WHERE id = :id');
    $requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
    $requete->execute();
	
	return $requete->fetch()[0];
  }
  
  /**
   * (non-PHPdoc)
   * @see \Library\Models\MembreManager::declasser()
   */
  public function declasser($id)
  {
  	$requete = $this->dao->prepare('UPDATE ' . self::NOM_TABLE . ' SET groupe=2 WHERE groupe = :id');
  	$requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
  	$requete->execute();
  }
  
  /**
   * (non-PHPdoc)
   * @see \Library\Models\MembreManager::updateGroupe()
   */
  public function updateGroupe($id, $groupe)
  {
  	$requete = $this->dao->prepare('
					            UPDATE ' . self::NOM_TABLE . '
					            SET
					                groupe = :groupe
					            WHERE id = :id');
  	 
  	$requete->bindValue(':groupe', (int) $groupe, \PDO::PARAM_INT);
  	$requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
  	$requete->execute();
  }
  
  /**
   * (non-PHPdoc)
   * @see \Library\Models\MembreManager::passation()
   */
  public function passation($nouveauPresident, $ancienPresident, $nouveauGroupe)
  {
  	$requete = $this->dao->prepare('
					            UPDATE ' . self::NOM_TABLE . '
					            SET
					                groupe = 1
					            WHERE id = :id');  	
  	$requete->bindValue(':id', (int) $nouveauPresident, \PDO::PARAM_INT);
  	$requete->execute();
  	
  	$requete = $this->dao->prepare('
					            UPDATE ' . self::NOM_TABLE . '
					            SET
					                groupe = :groupe
					            WHERE id = :id');
  	$requete->bindValue(':id', (int) $ancienPresident, \PDO::PARAM_INT);
  	$requete->bindValue(':groupe', (int) $nouveauGroupe, \PDO::PARAM_INT);
  	$requete->execute();
  }
}