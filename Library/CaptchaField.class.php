<?php
namespace Library;

class CaptchaField extends Field
{
	public function buildWidget()
	{
		$widget = '';

		if (!empty($this->errorMessage))
		{
			$widget .= $this->errorMessage.'<br />';
		}
		if(!empty($this->labelWidth) && $this->labelWidth > 0)
			$labelWidth = 'style="width:' . $this->labelWidth . 'px;"';
		else
			$labelWidth = '';

		$widget .= '<label for="' . $this->name . '" ' . $labelWidth . '>Recopiez le nombre ci-contre : <img id="image-captcha" src="captcha.php" alt="captcha" /></label><input type="text" name="'.$this->name.'" id="' . $this->name . '"';

		$widget .= ' /> <button type="button" title="Changer d\'image" class="bt-editeur icon-loop2" onclick="changerCaptcha()"></button>';
		
		return $widget;
	}
}