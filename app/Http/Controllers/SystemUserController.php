<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class SystemUserController extends Controller
{
    private const USERNAME_DOMAIN = '@gds.com';

    public function index()
    {
        $permissionConfig = config('permissions');
        $permissionGroups = $permissionConfig['groups'];
        $permissionActions = $permissionConfig['actions'];
        $permissionLabels = $this->permissionLabels();

        $panelIds = session('panel_user_ids', []);
        $users = empty($panelIds)
            ? collect()
            : User::whereIn('id', $panelIds)->orderBy('username')->get();

        return view('systeme.utilisateurs', compact(
            'users',
            'permissionGroups',
            'permissionActions',
            'permissionLabels'
        ));
    }

    public function store(Request $request)
    {
        $allPermissionKeys = $this->allPermissionKeys();

        $username = $this->formatUsername($request->input('username', ''));
        $request->merge(['username' => $username]);

        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'max:50',
                'unique:users,username',
                'regex:/^[a-z0-9._-]+@gds\.com$/i',
            ],
            'password' => ['required', Password::min(6)],
            'permissions' => 'required|array|min:1',
            'permissions.*' => ['required', 'string', Rule::in($allPermissionKeys)],
        ], [
            'username.regex' => 'Le nom d\'utilisateur ne peut contenir que des lettres, chiffres, points, tirets et underscores avant @gds.com.',
            'username.unique' => 'Ce nom d\'utilisateur existe déjà.',
            'permissions.required' => 'Cochez au moins une autorisation.',
            'permissions.min' => 'Cochez au moins une autorisation.',
        ]);

        $username = $validated['username'];
        $permissions = array_values($validated['permissions']);
        $localPart = strstr($username, '@', true) ?: $username;

        $user = User::create([
            'username' => $username,
            'name' => $localPart,
            'email' => $username,
            'password' => $validated['password'],
            'role' => $this->resolveRole($permissions),
            'permissions' => $permissions,
            'is_active' => true,
        ]);

        $panelIds = session('panel_user_ids', []);
        $panelIds[] = $user->id;
        session(['panel_user_ids' => array_values(array_unique($panelIds))]);

        return redirect()->route('systeme.utilisateurs.index')->with('success', 'Utilisateur ajouté au tableau.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Vous ne pouvez pas supprimer votre propre compte.']);
        }

        $panelIds = array_values(array_filter(
            session('panel_user_ids', []),
            fn ($id) => (int) $id !== $user->id
        ));
        session(['panel_user_ids' => $panelIds]);

        $user->delete();

        return redirect()->route('systeme.utilisateurs.index')->with('success', 'Utilisateur retiré du tableau.');
    }

    private function allPermissionKeys(): array
    {
        $keys = [];
        $actions = array_keys(config('permissions.actions', []));

        foreach (config('permissions.groups', []) as $modules) {
            foreach (array_keys($modules) as $moduleKey) {
                foreach ($actions as $action) {
                    $keys[] = $moduleKey.'.'.$action;
                }
            }
        }

        return $keys;
    }

    private function permissionLabels(): array
    {
        $labels = [];
        $actions = config('permissions.actions', []);

        foreach (config('permissions.groups', []) as $modules) {
            foreach ($modules as $moduleKey => $moduleLabel) {
                foreach ($actions as $actionKey => $actionMeta) {
                    $labels[$moduleKey.'.'.$actionKey] = $moduleLabel.' — '.$actionMeta['label'];
                }
            }
        }

        return $labels;
    }

    private function resolveRole(array $permissions): string
    {
        $hasModule = fn (string $module): bool => collect($permissions)
            ->contains(fn ($p) => str_starts_with($p, $module.'.'));

        if ($hasModule('systeme_utilisateurs')) {
            return 'admin';
        }

        $managerModules = [
            'operations_stock', 'operations_equipe', 'gestion_etat_travaux',
            'fournisseur_etat', 'gestion_rapport_travaux',
        ];

        foreach ($managerModules as $module) {
            if ($hasModule($module)) {
                return 'manager';
            }
        }

        return 'technician';
    }

    private function formatUsername(string $input): string
    {
        $local = strtolower(trim($input));
        $local = preg_replace('/@gds\.com$/i', '', $local) ?? $local;
        $local = preg_replace('/@.+$/', '', $local) ?? $local;

        return $local.self::USERNAME_DOMAIN;
    }
}
