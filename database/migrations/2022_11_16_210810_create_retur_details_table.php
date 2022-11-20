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
        Schema::create('retur_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_retur')->constrained('retur');
            $table->foreignId('id_product')->constrained('product');
            $table->integer('selling_price');
            $table->integer('qty');
            $table->tinyInteger('discount')->default(0);
            $table->integer('subtotal');
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
        Schema::dropIfExists('retur_details');
    }
};
