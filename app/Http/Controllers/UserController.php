<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function index()
    {
        return view('manager.users.index');
    }

    public function getUsers(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $users = $query->with('roles')->paginate(5);

        return response()->json($users);
    }

    public function create()
    {
        $roles = Role::all();

        return view('manager.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $validatedData = $request->validated();

        $validatedData['password'] = bcrypt($validatedData['password']);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
        ]);

        $role = Role::find($validatedData['role_id']);

        if ($role) {
            $user->assignRole($role->name);
        }

        return redirect()->route('manager.users.index')->with('success', 'User added successfully');
    }

    public function edit(User $user)
    {
        $roles = Role::all();

        return view('manager.users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validatedData = $request->validated();

        // Menghapus role yang ada (jika ada)
        $user->roles()->detach();

        // Mengupdate data pengguna
        $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
        ]);

        // Menetapkan role baru
        if (isset($validatedData['role_id'])) {
            $role = Role::find($validatedData['role_id']);
            if ($role) {
                $user->assignRole($role->name);
            }
        }

        return redirect()->route('manager.users.index')->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('manager.users.index')->with('success', 'User deleted successfully');
    }
}
