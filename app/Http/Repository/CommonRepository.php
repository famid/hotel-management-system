<?php
/**
 * Created by PhpStorm.
 * User: debu
 * Date: 7/5/19
 * Time: 4:17 PM
 */

namespace App\Http\Repository;


class CommonRepository
{
    public $model;

    /**
     * CommonRepository constructor.
     * @param $model
     */
    function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        return $this->model->create($data);
    }

    /**
     * @param $where
     * @param $data
     * @return mixed
     */
    public function update($where, $data)
    {
        return $this->model->where($where)->update($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->model->where('id', $id)->update(['status' => DELETE_STATUS]);
    }

    /**
     * @param $where
     * @return mixed
     */
    public function deleteWhere($where)
    {
        return $this->model->where($where)->delete();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->model->where('id', $id)->first();
    }

    /**
     * @param array $relation
     * @return mixed
     */
    public function getAll($relation = [])
    {
        return $this->model->with($relation)->get();
    }

    /**
     * @param array $where
     * @param array $relation
     * @param string[] $orderBy
     * @return mixed
     */
    public function whereFirst($where = [], $orderBy = ['id', 'ASC'], $relation = [])
    {
        return $this->model->where($where)->with($relation)->orderBy($orderBy[0], $orderBy[1])->first();
    }

    /**
     * @param array $where
     * @param array $relation
     * @return mixed
     */
    public function getWhere($where = [], $relation = [])
    {
        return $this->model->where($where)->with($relation)->get();
    }

    /**
     * @param $select
     * @param $where
     * @param array $relation
     * @param int $paginate
     * @return mixed
     */
    public function selectWhere($select, $where, $relation = [], $paginate = 0)
    {
        if ($paginate === 0) {
            return $this->model->select($select)->where($where)->with($relation)->get();
        }

        return $this->model->select($select)->where($where)->with($relation)->paginate($paginate);
    }
}
