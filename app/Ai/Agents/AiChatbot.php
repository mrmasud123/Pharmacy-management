<?php

namespace App\Ai\Agents;

use App\Ai\Tools\DatabaseQueryTool;
use App\Ai\Tools\ListAllMedicineTools;
use App\Ai\Tools\ListRolesWithPermissionsTool;
use Illuminate\Support\Facades\DB;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;

class AiChatbot implements Agent, Conversational, HasTools
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    protected string $provider = "groq";
    protected string $model = "llama-3.3-70b-versatile";


    /**
     * Get the list of messages comprising the conversation so far.
     *
     * @return Message[]
     */
    public function messages(): iterable
    {
        return [];
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function instructions(): Stringable|string
    {
        // Fetch table structure so AI knows what to query
        $tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = DATABASE()");
        $schema = collect($tables)->map(function ($table) {
            $tableName = $table->table_name ?? $table->TABLE_NAME;
            $columns   = DB::select("DESCRIBE `{$tableName}`");
            $cols      = collect($columns)->pluck('Field')->join(', ');
            return "{$tableName}: ({$cols})";
        })->join("\n");

        return "
        You are a helpful pharmacy management assistant.
        You have access to a DatabaseQueryTool that runs SELECT queries.

        Here is the full database schema:
        {$schema}

        Rules:
        - Always use DatabaseQueryTool to fetch real data before answering.
        - Only write SELECT queries — never mutate data.
        - Use JOINs when data spans multiple tables.
        - Format responses clearly using markdown tables.
        - Never guess or invent data.
    ";
    }

    public function tools(): iterable
    {
        return [
            new DatabaseQueryTool(),
        ];
    }
}
