<?php
namespace App\Repositories\User;

use App\Models\User as UserModel;

class EloquentUser implements UserInterface
{
	private $model;

	public function __construct(UserModel $model)
    {
        $this->model = $model;
    }

	public function getAll()
    {
        return $this->model->all();
    }

	public function getById($id)
	{
		return $this->model->findById($id);
	}

	public function create(array $attributes)
	{
		return $this->model->create($attributes);
	}

	public function update($id, array $attributes)
	{
		$todo = $this->model->findOrFail($id);
		$todo->update($attributes);
		return $todo;
	}

	public function delete($id)
	{
		$this->getById($id)->delete();
		return true;
	}
}