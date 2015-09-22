<?php
/**
 * Classe abstraite représentant un mail reçu par le club.
 * @author Siméon Capy
 * @version 1.0
 */
namespace Library\Entities;
 
class MailReception extends Mail
{
  protected $expediteur,
  			$lu;
   
  // ----------------------------------------
  // ************** MÉTHODES ****************
  // ----------------------------------------
   
		// Néant
   
  // ---------------------------------------
  // ************** SETTERS ****************
  // ---------------------------------------
  
	public function setExpediteur($exp)
	{
		$this->expediteur = (string) $exp;
	}
	
	public function setLu($lu)
	{
		$this->lu = $lu;
	}

		   
  // ---------------------------------------
  // ************** GETTERS ****************
  // ---------------------------------------
   
	public function expediteur()
	{
		return $this->expediteur;
	}
	
	public function lu()
	{
		return $this->lu;
	}
}