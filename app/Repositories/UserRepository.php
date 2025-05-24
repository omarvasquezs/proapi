<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    /**
     * UserRepository constructor.
     * 
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * Obtener usuarios con roles
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUsersWithRoles()
    {
        return $this->model->with('roles')->get();
    }

    /**
     * Obtener usuarios con roles y permisos
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUsersWithRolesAndPermissions()
    {
        return $this->model->with(['roles.permissions'])->get();
    }

    /**
     * Buscar usuario por correo electrónico
     * 
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * Asignar roles a un usuario
     * 
     * @param int $userId
     * @param array $roles
     * @return User
     */
    public function assignRoles(int $userId, array $roles): User
    {
        $user = $this->findOrFail($userId);
        $user->syncRoles($roles);
        return $user;
    }
} 