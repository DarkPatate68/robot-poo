<?php
namespace Library;

/**
 * Classe représentant un champ générique, elle est abstraite. Elle définie les paramètres communs à tous les champs
 * ainsi que la fonction d'hydratation et le constructeur.
 * @author Siméon
 * @date 29/06/2014
 *
 */
abstract class Field
{
	protected $errorMessage; /**< Liste des erreurs rencontrées lors de la création du champ */
	protected $label; /**< Paramètre label du champ (paramètre HTML) */
	protected $name; /**< Paramètre name du champ (paramètre HTML) */
	protected $value; /**< Paramètre value du champ (paramètre HTML) */
	protected $title; /**< Paramètre title du champ (paramètre HTML) */
	protected $validators = array(); /**< Liste des validateur à appliquer au champ */

	/**
	 * Constructeurs. Appel la fonction self::hydrate() pour une constructuion "automatique"
	 * @param array $options
	 */
	public function __construct(array $options = array())
	{
		if (!empty($options))
		{
			$this->hydrate($options);
		}
	}

	/**
	 * Fonction abstraite définie dans les classes filles. C'est elle qui est appellée pour généré le code HTML
	 * du champ.
	 */
	abstract public function buildWidget();

	/**
	 * Fonction d'hydration qui créé l'objet en fonction des paramètres reçu dans le tableau.
	 * @param array $options
	 */
	public function hydrate($options)
	{
		foreach ($options as $type => $value)
		{
			$method = 'set'.ucfirst($type);

			if (is_callable(array($this, $method)))
			{
				$this->$method($value);
			}
		}
	}

	/**
	 * Vérifie tous les validateurs, si l'un d'eu renvoie faux alors le champ est déclaré comme invalide.
	 * @return boolean
	 */
	public function isValid()
	{
		foreach ($this->validators as $validator)
		{
			if (!$validator->isValid($this->value))
			{
				$this->errorMessage = $validator->errorMessage();
				return false;
			}
		}

		return true;
	}
	
	/**
	 * Version française de self::isValid()
	 * @return boolean
	 */
	public function estValide()
	{
		return $this->isValid();
	}

	/**
	 * Accesseur en lecture du champ label
	 * @return string
	 */
	public function label()
	{
		return $this->label;
	}

	/**
	 * Accesseur en lecture du champ name
	 * @return string
	 */
	public function name()
	{
		return $this->name;
	}
	
	/**
	 * Accesseur en lecture du champ title
	 * @return string
	 */
	public function title()
	{
		return $this->title;
	}

	/**
	 * Accesseur en lecture du champ value
	 * @return string
	 */
	public function value()
	{
		return $this->value;
	}
	
	/**
	 * Accesseur en lecture des validateurs
	 * @return multitype:
	 */
	public function validators()
	{
		return $this->validators;
	}

	/**
	 * Accesseur en écriture du champ label
	 * @param string $label
	 */
	public function setLabel($label)
	{
		if (is_string($label))
		{
			$this->label = $label;
		}
	}

	/**
	 * Accesseur en écriture du champ name
	 * @param string $name
	 */
	public function setName($name)
	{
		if (is_string($name))
		{
			$this->name = $name;
		}
	}
	
	/**
	 * Accesseur en écriture du champ title
	 * @param string $title
	 */
	public function setTitle($title)
	{
		if (is_string($title))
		{
			$this->title = $title;
		}
	}

	/**
	 * Accesseur en écriture du champ value
	 * @param string $value
	 */
	public function setValue($value)
	{	
		$this->value = $value;
	}
	
	/**
	 * Accesseur en écriture de validators.
	 * Prend en entrée un tableau de \Library\Validator.
	 * @param array $validators
	 */
	public function setValidators(array $validators)
	{
		foreach ($validators as $validator)
		{
			if ($validator instanceof Validator && !in_array($validator, $this->validators))
			{
				$this->validators[] = $validator;
			}
		}
	}
}