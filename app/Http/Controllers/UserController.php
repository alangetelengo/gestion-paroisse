<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Helpers\FlashAlert;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Traits\LogsErrors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    use LogsErrors;

    /**
     * Affiche la liste des utilisateurs
     */
    public function index()
    {
        try {
            $users = User::with(['paroisse', 'roles'])
                ->when(auth()->check() && auth()->user()->hasRole('super_admin'), function ($query) {
                    // Super admin voit tous les utilisateurs
                    return $query;
                }, function ($query) {
                    // Autres rôles voient seulement les utilisateurs de leur paroisse
                    if (auth()->check() && auth()->user()->paroisse_id) {
                        return $query->where('paroisse_id', auth()->user()->paroisse_id);
                    }
                    return $query;
                })
                ->latest()
                ->paginate(15);

            return view('users.index', compact('users'));
        } catch (\Exception $e) {
            $this->logError('Erreur lors de la récupération des utilisateurs', $e);
            FlashAlert::error('Une erreur est survenue lors de la récupération des utilisateurs.');
            return redirect()->route('dashboard');
        }
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::query()->orderBy('name')->get();
        $paroisses = \App\Models\Paroisse::where('actif', true)->get();

        // Si super admin, voir toutes les paroisses, sinon seulement la sienne
        if (auth()->check() && !auth()->user()->hasRole('super_admin') && auth()->user()->paroisse_id) {
            $paroisses = $paroisses->where('id', auth()->user()->paroisse_id);
        }

        return view('users.create', compact('roles', 'permissions', 'paroisses'));
    }

    /**
     * Enregistre un nouvel utilisateur
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $validated = $request->validated();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'paroisse_id' => $validated['paroisse_id'] ?? null,
            ]);

            if (empty($user->username)) {
                $base = (string) Str::of((string) $user->email)
                    ->before('@')
                    ->lower()
                    ->replaceMatches('/[^a-z0-9_.-]+/', '')
                    ->trim('-_.');

                if ($base === '') {
                    $base = 'user';
                }

                $user->update(['username' => "{$base}-{$user->id}"]);
            }

            // Assigner les rôles
            $user->syncRoles($validated['roles']);

            // Permissions directes
            $permissionIds = $validated['permissions'] ?? [];
            $user->syncPermissions(
                ! empty($permissionIds)
                    ? Permission::whereIn('id', $permissionIds)->get()
                    : []
            );

            $this->logInfo("Utilisateur créé : {$user->email}", ['user_id' => $user->id]);
            FlashAlert::success("L'utilisateur a été créé avec succès.");

            return redirect()->route('users.index');
        } catch (\Exception $e) {
            $this->logError('Erreur lors de la création de l\'utilisateur', $e, ['data' => $request->except('password')]);
            FlashAlert::error('Une erreur est survenue lors de la création de l\'utilisateur.');
            return back()->withInput();
        }
    }

    /**
     * Affiche un utilisateur
     */
    public function show(User $user)
    {
        $user->load(['paroisse', 'roles', 'permissions']);
        return view('users.show', compact('user'));
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::query()->orderBy('name')->get();
        $paroisses = \App\Models\Paroisse::where('actif', true)->get();
        $user->load(['roles', 'permissions']);

        // Si super admin, voir toutes les paroisses, sinon seulement la sienne
        if (auth()->check() && !auth()->user()->hasRole('super_admin') && auth()->user()->paroisse_id) {
            $paroisses = $paroisses->where('id', auth()->user()->paroisse_id);
        }

        return view('users.edit', compact('user', 'roles', 'permissions', 'paroisses'));
    }

    /**
     * Met à jour un utilisateur
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $validated = $request->validated();

            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'paroisse_id' => $validated['paroisse_id'] ?? null,
            ]);

            if (empty($user->username)) {
                $base = (string) Str::of((string) $user->email)
                    ->before('@')
                    ->lower()
                    ->replaceMatches('/[^a-z0-9_.-]+/', '')
                    ->trim('-_.');

                if ($base === '') {
                    $base = 'user';
                }

                $user->update(['username' => "{$base}-{$user->id}"]);
            }

            // Mettre à jour le mot de passe si fourni
            if (!empty($validated['password'])) {
                $user->update(['password' => Hash::make($validated['password'])]);
            }

            // Synchroniser les rôles
            $user->syncRoles($validated['roles']);

            // Synchroniser les permissions directes
            $permissionIds = $validated['permissions'] ?? [];
            $user->syncPermissions(
                ! empty($permissionIds)
                    ? Permission::whereIn('id', $permissionIds)->get()
                    : []
            );

            $this->logInfo("Utilisateur mis à jour : {$user->email}", ['user_id' => $user->id]);
            FlashAlert::success("L'utilisateur a été mis à jour avec succès.");

            return redirect()->route('users.index');
        } catch (\Exception $e) {
            $this->logError('Erreur lors de la mise à jour de l\'utilisateur', $e, [
                'user_id' => $user->id,
                'data' => $request->except('password')
            ]);
            FlashAlert::error('Une erreur est survenue lors de la mise à jour de l\'utilisateur.');
            return back()->withInput();
        }
    }

    /**
     * Supprime un utilisateur
     */
    public function destroy(User $user)
    {
        try {
            // Ne pas permettre la suppression de soi-même
            if ($user->id === auth()->id()) {
                FlashAlert::error('Vous ne pouvez pas supprimer votre propre compte.');
                return back();
            }

            $userEmail = $user->email;
            $user->delete();

            $this->logInfo("Utilisateur supprimé : {$userEmail}", ['user_id' => $user->id]);
            FlashAlert::success("L'utilisateur a été supprimé avec succès.");

            return redirect()->route('users.index');
        } catch (\Exception $e) {
            $this->logError('Erreur lors de la suppression de l\'utilisateur', $e, ['user_id' => $user->id]);
            FlashAlert::error('Une erreur est survenue lors de la suppression de l\'utilisateur.');
            return back();
        }
    }
}
