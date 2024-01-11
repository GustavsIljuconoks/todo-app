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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id('task_id');
            $table->string('name');
            $table->string('description');
            $table->boolean('completed')->default(0);
            $table->date('due_date');
            $table->unsignedBigInteger('priorities_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('priorities_id')
                ->references('priorities_id')
                ->on('priorities')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign('tasks_priorities_id_foreign');
            $table->dropIndex('tasks_priorities_id_index');
            $table->dropColumn('priorities_id');
        });
    }
};
