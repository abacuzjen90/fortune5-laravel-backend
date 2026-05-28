<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up()
    {
        Schema::create('sys_accountsub', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('haccountid');
            $table->text('subtitle');
            $table->bigInteger('subsequenceno');
            $table->timestamps();

            // Add foreign key constraint if needed
            // $table->foreign('haccountid')->references('id')->on('sys_accountheader');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sys_accountsub');
    }
};
