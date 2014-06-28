<?php
namespace Library;

class CheckboxField extends Field
{
	public function buildWidget()
	{
		$widget = '';

		if (!empty($this->errorMessage))
		{
			$widget .= $this->errorMessage.'<br />';
		}
		
		$widget .= '<label for="' . $this->name . '"';
		
		if (!empty($this->title))
		{
			$widget .= ' title="' . $this->title . '"';
		}

		$widget .= '>'.$this->label.'</label><input type="checkbox" name="'.$this->name.'" id="' . $this->name . '"';

		if ($this->value)
		{
			$widget .= ' checked="checked"';
		}

		return $widget .= ' />';
	}
}