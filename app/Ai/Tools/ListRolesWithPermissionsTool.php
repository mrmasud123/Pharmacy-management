<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Spatie\Permission\Models\Role;
use Stringable;

class ListRolesWithPermissionsTool implements Tool
{
    public function description(): Stringable|string
    {
        return 'Fetches all roles in the system along with their assigned permissions. Use this when the user asks which permissions belong to which roles, or wants to see role-permission mappings.';
    }

    public function handle(Request $request): Stringable|string
    {
        $results = Role::with('permissions')
            ->get()
            ->map(fn($role) => [
                'role'        => $role->name,
                'permissions' => $role->permissions->pluck('name')->toArray(),
            ]);

        return json_encode($results);
    }

    public function schema(JsonSchema $schema): array
    {
        return []; // no params needed
    }
}
