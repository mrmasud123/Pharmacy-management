<?php

namespace App\Ai\Tools;

use App\Models\Product;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Spatie\Permission\Models\Permission;
use Stringable;

class ListAllMedicineTools implements Tool
{
    public function description(): Stringable|string
    {
        return 'Lists medicines/permissions from the pharmacy database. Use this when the user asks about available medicines, drugs, or products.';
    }

    /**
     * Execute the tool — this is what the AI calls.
     */
    public function handle(Request $request): Stringable|string
    {
        $params = $request->all(); // ✅ correct method

        $query = Permission::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->string('search') . '%');
        }

        $limit = $request->integer('limit') ?: 50;

        $results = $query
            ->select('id', 'name')
            ->limit($limit)
            ->get()
            ->toArray();

        return json_encode($results);
    }

    /**
     * Define what parameters the AI can pass to this tool.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'search' => $schema->string()->description('Optional search term to filter medicines by name'),
            'limit'  => $schema->integer()->description('Max number of results to return, default 50'),
        ];
    }
}
