<?php
namespace OmniBlog\Model;

class Comment
{
	public $id;
	public $content;

	public function exchangeArray($data)
	{
		$this->id     = (isset($data['id'])) ? $data['id'] : null;
		$this->content  = (!empty($data['content'])) ? $data['content'] : null;
	}
}