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
		return $this->model->find($id);
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
		$data = $this->getById($id)->delete();
		return "deleted!";
	}

	public function listByPage($page)
	{
		$limit = 10;
        $offset = ($page - 1) * 10;

        $data =  $this->model->offset( $offset )
            ->limit( $limit )
            ->get();

        $data_count = $this->model->count();
        $total_pages = ceil($data_count/$limit);

        $pagination = [
            'active' => $page,
            'pages' => $total_pages,
            'prev' => ( $page <= 1 ? false : ($page - 1) ),
            'next' => ( $total_pages == $page ? false : ($page + 1) ),
        ];

        return [
            'data' => $data,
            'pagination' => $pagination
        ];
	}
}