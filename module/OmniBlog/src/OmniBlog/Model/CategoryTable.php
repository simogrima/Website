<?php
namespace OmniBlog\Model;

use Zend\Db\TableGateway\TableGateway;

class CategoryTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}

	public function getModel($ID)
	{
		$id  = (int) $ID;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}

	public function saveCategory(Category $model)
	{
		$data = array(
				'title'  => $model->title,
				'content' => $model->content,
		);

		$id = (int)$model->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
		} else {
			if ($this->getModel($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('Post id does not exist');
			}
		}
	}

	public function deleteModel($id)
	{
		$this->tableGateway->delete(array('id' => $id));
	}
}