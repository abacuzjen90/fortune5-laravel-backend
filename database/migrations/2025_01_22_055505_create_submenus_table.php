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
        Schema::create('sys_submenu', function (Blueprint $table) {
            $table->id();
            $table->string("menu_id");
            $table->string("submenu_name");
            $table->string("secondlevel")->nullable();
            $table->string("path_direction")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_submenu');
    }
};
