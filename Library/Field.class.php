<?php
namespace Library;

abstract class Field
{
	protected $errorMessage;
	protected $label;
	protected $name;
	protected $value;
	protected $title;
	protected $validators = array();

	public function __construct(array $options = array())
	{
		if (!empty($options))
		{
			$this->hydrate($options);
		}
	}

	abstract public function buildWidget();

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
	
	public function estValide()
	{
		return $this->isValid();
	}

	public function label()
	{
		return $this->label;
	}

	public function name()
	{
		return $this->name;
	}
	
	public function title()
	{
		return $this->title;
	}

	public function value()
	{
		return $this->value;
	}
	
	public function validators()
	{
		return $this->validators;
	}

	public function setLabel($label)
	{
		if (is_string($label))
		{
			$this->label = $label;
		}
	}

	public function setName($name)
	{
		if (is_string($name))
		{
			$this->name = $name;
		}
	}
	
	public function setTitle($title)
	{
		if (is_string($title))
		{
			$this->title = $title;
		}
	}

	public function setValue($value)
	{	
		$this->value = $value;
	}
	
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