<?php
namespace Library;

abstract class FormBuilder
{
	protected $form;

	public function __construct(Entity $entity, $value = null)
	{
		$this->setForm(new Form($entity, $value));
	}

	abstract public function build();

	public function setForm(Form $form)
	{
		$this->form = $form;
	}

	public function form()
	{
		return $this->form;
	}
}