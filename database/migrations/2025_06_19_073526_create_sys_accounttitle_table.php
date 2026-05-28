<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
      public function up()
    {
        Schema::create('sys_accounttitle', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('haccountid');
            $table->bigInteger('saccountid');
            $table->text('title');
            $table->text('description');
            $table->bigInteger('tsequenceno');
            $table->string('chartno', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_accounttitle');
    }
};
