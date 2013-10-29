<?php
namespace OmniBlog\Model;

class Category
{
	public $id;
	public $title;
	public $content;

	public function exchangeArray($data)
	{
		$this->id     = (isset($data['id'])) ? $data['id'] : null;
		$this->title  = (!empty($data['title'])) ? $data['title'] : null;
		$this->content  = (!empty($data['content'])) ? $data['content'] : null;
	}
}