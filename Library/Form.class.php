<?php
namespace Library;

class Form
{
	protected $entity;
	protected $fields;
	protected $value;
	protected $hasFileField;

	public function __construct(Entity $entity, $value = null)
	{
		$this->setEntity($entity);
		if($value != NULL)
			$this->setValue($value);
		$hasFileField = false;
	}

	public function add(Field $field)
	{
		$attr = $field->name(); // On récupère le nom du champ.
		if($field instanceof \Library\FileField) // Pour un champ de fichier, il n'y a pas de valeur
			$hasFileField = true;
		else if(!($field instanceof \Library\CaptchaField) && method_exists($this->entity, $attr))
			$field->setValue($this->entity->$attr()); // On assigne la valeur correspondante au champ.
		else if(method_exists($field, 'value')) // Si un attribut value est définit
			$field->setValue($this->value[$field->value()]);
		else
			$field->setValue($this->value[$field->name()]);

		$this->fields[] = $field; // On ajoute le champ passé en argument à la liste des champs.
		return $this;
	}

	public function createView()
	{
		$view = '';

		// On génère un par un les champs du formulaire.
		foreach ($this->fields as $field)
		{
			$view .= $field->buildWidget();
			if(!($field instanceof \Library\HiddenField))
				$view .= '<br />';
		}

		return $view;
	}

	public function isValid()
	{
		$valid = true;

		// On vérifie que tous les champs sont valides.
		foreach ($this->fields as $field)
		{
			if (!$field->isValid())
				$valid = false;
		}

		return $valid;
	}
	
	public function estValide()
	{
		return $this->isValid();
	}

	public function entity()
	{
		return $this->entity;
	}
	
	public function hasFileField()
	{
		return $this->hasFileField;
	}

	public function setEntity(Entity $entity)
	{
		$this->entity = $entity;
	}
	
	public function setValue(array $value)
	{
		$this->value = $value;
	}
}