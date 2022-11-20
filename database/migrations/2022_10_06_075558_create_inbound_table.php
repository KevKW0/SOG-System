<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inbound', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_supplier')->constrained('supplier');
            $table->integer('total_item');
            $table->integer('total_price');
            $table->tinyInteger('discount')->default(0);
            $table->integer('pay')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inbound');
    }
};
