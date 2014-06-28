<?php
namespace Library;

class LineEditField extends Field
{
	protected $maxLength;
	protected $disabled;

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
		
		$widget .= '>'.$this->label.'</label><input type="text" name="'.$this->name.'" id="' . $this->name . '"';
		
		if (!empty($this->value))
		{
			if($this->value instanceof \Library\Entities\Membre)
				$widget .= ' value="' . $this->value->usuel() . '"';
			else
				$widget .= ' value="' . $this->value . '"';
		}

		if (!empty($this->maxLength) && $this->maxLength > 0)
		{
			$widget .= ' maxlength="'.$this->maxLength.'"';
		}
		
		if ($this->disabled)
		{
			$widget .= ' readonly="true"';
		}

		return $widget .= ' />';
	}

	public function setMaxLength($maxLength)
	{
		$this->maxLength = (int) $maxLength;
	}
	
	public function setDisabled($dis)
	{
		$this->disabled = (bool) $dis;
	}
}