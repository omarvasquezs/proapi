<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    protected $userRepository;    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->middleware('permission:ver-usuarios')->only(['index', 'show']);
        $this->middleware('permission:crear-usuarios')->only(['store']);
        $this->middleware('permission:editar-usuarios')->only(['update']);
        $this->middleware('permission:eliminar-usuarios')->only(['destroy']);
        $this->middleware('permission:asignar-roles')->only(['assignRole', 'removeRole']);
    }

    /**
     * Mostrar todos los usuarios con sus roles
     */
    public function index()
    {
        $users = $this->userRepository->getUsersWithRoles();
        return new UserCollection($users);
    }

    /**
     * Almacenar un nuevo usuario
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
        ]);

        $user = $this->userRepository->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        return new UserResource($user->load('roles'));
    }

    /**
     * Mostrar un usuario específico
     */
    public function show($id)
    {
        $user = $this->userRepository->findOrFail($id);
        return new UserResource($user->load('roles'));
    }

    /**
     * Actualizar un usuario
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:8',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
        ]);

        $user = $this->userRepository->findOrFail($id);
        
        $data = $request->only(['name', 'email']);
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        
        $this->userRepository->update($data, $id);

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        return new UserResource($user->load('roles'));
    }

    /**
     * Eliminar un usuario
     */
    public function destroy($id)
    {
        $user = $this->userRepository->findOrFail($id);
        $this->userRepository->delete($id);
        
        return response()->json(['message' => 'Usuario eliminado correctamente']);
    }

    /**
     * Asignar rol a un usuario
     */
    public function assignRole(Request $request, $id)
    {
        $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
        ]);

        $user = $this->userRepository->findOrFail($id);
        $user = $this->userRepository->assignRoles($id, $request->roles);

        return new UserResource($user->load('roles'));
    }

    /**
     * Remover rol de un usuario
     */
    public function removeRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $user = $this->userRepository->findOrFail($id);
        $user->removeRole($request->role);

        return new UserResource($user->load('roles'));
    }
} 