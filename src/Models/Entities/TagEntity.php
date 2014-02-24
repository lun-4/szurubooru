<?php
class TagEntity extends AbstractEntity
{
	public $name;

	public function getPostCount()
	{
		$stmt = new SqlSelectStatement();
		$stmt->setColumn(new SqlAliasFunctor(new SqlCountFunctor('1'), 'count'));
		$stmt->setTable('post_tag');
		$stmt->setCriterion(new SqlEqualsFunctor('tag_id', new SqlBinding($this->id)));
		return Database::fetchOne($stmt)['count'];
	}
}
