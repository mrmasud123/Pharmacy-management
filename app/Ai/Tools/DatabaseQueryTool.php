<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\DB;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class DatabaseQueryTool implements Tool
{
    public function description(): Stringable|string
    {
        return 'Executes a raw SELECT SQL query on the database and returns the results.
                Use this tool to answer any question about the application data.
                Only SELECT statements are allowed — never INSERT, UPDATE, DELETE, or DROP.';
    }

    public function handle(Request $request): Stringable|string
    {
        $sql = $request->string('sql');

        // ✅ Safety guard — only allow SELECT
        $normalized = strtoupper(trim($sql));
        if (!str_starts_with($normalized, 'SELECT')) {
            return json_encode(['error' => 'Only SELECT queries are allowed.']);
        }

        try {
            $results = DB::select($sql);
            return json_encode($results);
        } catch (\Exception $e) {
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'sql' => $schema->string()->description('A valid SELECT SQL query to run against the database.')->required(),
        ];
    }
}
