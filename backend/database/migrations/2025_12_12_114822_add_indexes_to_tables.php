<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->index('created_at');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->index('project_id');
            $table->index('completed');
            $table->index(['project_id', 'completed']);
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex(['project_id']);
            $table->dropIndex(['completed']);
            $table->dropIndex(['project_id', 'completed']);
        });
    }
};
