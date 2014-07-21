<?php
namespace Library;

class HiddenField extends Field
{

	public function buildWidget()
	{
		$widget = '';

		if (!empty($this->errorMessage))
		{
			$widget .= $this->errorMessage.'<br />';
		}

		if($this->value instanceof \Library\Entities\Membre)
			$value = (string) $this->value->id();
		else
			$value = (string) $this->value;
		
		$widget .= '<input type="hidden" name="'.$this->name.'" value="'.$value.'"';

		return $widget .= ' />';
	}

}