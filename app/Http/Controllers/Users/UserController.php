<?php

namespace App\Http\Controllers\Users;

use App\Data\UserData;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();

        return Inertia::render('Users/Index', [
            'users' => UserData::collect($users),
            'roles' => UserRole::cases(),
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $temporaryCode = Str::random(8);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => null,
            'temporary_password' => Hash::make($temporaryCode),
            'must_change_password' => true,
        ]);

        return redirect()
            ->route('users.index')
            ->with('temporary_code', $temporaryCode);
    }

    public function update(StoreUserRequest $request, User $user)
    {
        $validated = $request->validated();

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ]);

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index');
    }

    public function changePasswordView(User $user)
    {
        if (auth()->id() !== $user->id) {
            abort(403, 'Você só pode alterar sua própria senha por este caminho.');
        }

        return Inertia::render('Users/ChangePassword', [
            'user' => UserData::from($user),
        ]);
    }

    public function updatePassword(Request $request, User $user)
    {
        if (auth()->id() !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
            'temporary_password' => null,
            'must_change_password' => false,
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'Senha atualizada com sucesso!');
    }
}
