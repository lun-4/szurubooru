<?php
class TagSearchParser extends AbstractSearchParser
{
	protected function processSetup(&$tokens)
	{
		$allowedSafety = PrivilegesHelper::getAllowedSafety();
		$this->statement
			->addInnerJoin('post_tag', new SqlEqualsFunctor('tag.id', 'post_tag.tag_id'))
			->addInnerJoin('post', new SqlEqualsFunctor('post.id', 'post_tag.post_id'))
			->setCriterion((new SqlConjunctionFunctor)->add(SqlInFunctor::fromArray('safety', SqlBinding::fromArray($allowedSafety))))
			->setGroupBy('tag.id');
	}

	protected function processSimpleToken($value, $neg)
	{
		if ($neg)
			return false;

		if (strlen($value) >= 3)
			$value = '%' . $value;
		$value .= '%';

		$this->statement->getCriterion()->add(new SqlNoCaseFunctor(new SqlLikeFunctor('tag.name', new SqlBinding($value))));
		return true;
	}

	protected function processOrderToken($orderByString, $orderDir)
	{
		if ($orderByString == 'popularity')
			$this->statement->setOrderBy('post_count', $orderDir);
		elseif ($orderByString == 'alpha')
			$this->statement->setOrderBy('tag.name', $orderDir);
		else
			return false;
		return true;
	}
}
