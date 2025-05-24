<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     * 
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Obtener todos los registros.
     * 
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->with($relations)->get($columns);
    }

    /**
     * Obtener todos los registros truncados.
     * 
     * @param int $limit
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function allTruncated(int $limit = 10, array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->with($relations)->limit($limit)->get($columns);
    }

    /**
     * Obtener todos los registros paginados.
     * 
     * @param int $perPage
     * @param array $columns
     * @param array $relations
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $columns = ['*'], array $relations = []): LengthAwarePaginator
    {
        return $this->model->with($relations)->paginate($perPage, $columns);
    }

    /**
     * Crear un nuevo registro.
     * 
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Actualizar un registro existente.
     * 
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update(array $data, int $id): bool
    {
        return $this->model->find($id)->update($data);
    }

    /**
     * Eliminar un registro.
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->model->find($id)->delete();
    }

    /**
     * Buscar un registro por su ID.
     * 
     * @param int $id
     * @param array $columns
     * @param array $relations
     * @return Model|null
     */
    public function find(int $id, array $columns = ['*'], array $relations = []): ?Model
    {
        return $this->model->with($relations)->find($id, $columns);
    }

    /**
     * Buscar un registro por su ID o lanzar una excepción.
     * 
     * @param int $id
     * @param array $columns
     * @param array $relations
     * @return Model
     */
    public function findOrFail(int $id, array $columns = ['*'], array $relations = []): Model
    {
        return $this->model->with($relations)->findOrFail($id, $columns);
    }

    /**
     * Buscar registros por condiciones.
     * 
     * @param array $conditions
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function findWhere(array $conditions, array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->with($relations)->where($conditions)->get($columns);
    }
} 