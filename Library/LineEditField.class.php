<?php
namespace Library;

class LineEditField extends Field
{
	protected $maxLength;
	protected $disabled;
	protected $pattern;
	protected $width;

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
		
		if ($this->width > 0)
		{
			$widget .= ' style="width: ' . (int) $this->width . 'px;"';
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
		
		
		if(!empty($this->pattern))
		    $widget .= ' pattern="' . $this->pattern . '"';

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
	
	public function setPattern($pattern)
	{
	    $this->pattern = (string) $pattern;
	}
	
	public function setWidth($width)
	{
		$this->width = (int) $width;
	}
}