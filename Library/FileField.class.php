<?php
namespace Library;

class FileField extends Field
{
	protected $maxSize;
	protected $disabled;

	public function buildWidget()
	{
		$widget = '';

		if (!empty($this->errorMessage))
		{
			$widget .= $this->errorMessage.'<br />';
		}
		
		if (!empty($this->maxSize) && $this->maxSize > 0)
		{
			$widget .= '<input type="hidden" name="MAX_FILE_SIZE" value="' . $this->maxSize . '" />';
		}
		
		$widget .= '<label for="' . $this->name . '" ';
		
		if(!empty($this->title))
		{
			$widget .= 'title="' . $this->title . '"';
		}
		
		$widget .= '>'.$this->label.'</label><input type="file" name="'.$this->name.'" id="' . $this->name . '"';

		if ($this->disabled)
		{
			$widget .= ' readonly="true"';
		}

		return $widget .= ' />';
	}

	public function setSize($maxSize)
	{
		$this->maxSize = (int) $maxSize;
	}
	
	public function setDisabled($dis)
	{
		$this->disabled = (bool) $dis;
	}
}