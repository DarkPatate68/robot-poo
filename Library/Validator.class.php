<?php
namespace Library;

/**
 * Classe de base de tous les validateurs, definie le seul attribut commun (errorMessage) ainsi que ses accesseurs.
 * @author Siméon
 * @date 29/06/2014
 *
 */
abstract class Validator
{
	protected $errorMessage; /**< Si la condition du validateur n'est pas rempli, le message d'erreur à afficher y est stocké */

	/**
	 * Constructeur de la classe, remplie la variable errorMessage
	 * @param string $errorMessage
	 */
	public function __construct($errorMessage)
	{
		$this->setErrorMessage($errorMessage);
	}

	abstract public function isValid($value);

	/**
	 * Définie l'accesseur en écriture de la classe abstraite pour ses classes filles
	 * @param string $errorMessage Si le paramètre n'est pas une chaîne de caractère, rien ne sera enregistré
	 */
	public function setErrorMessage($errorMessage)
	{
		if (is_string($errorMessage))
		{
			$this->errorMessage = $errorMessage;
		}
	}

	/**
	 * Accesseur en lecture de la variable errorMessage
	 * @return string
	 */
	public function errorMessage()
	{
		return $this->errorMessage;
	}
}