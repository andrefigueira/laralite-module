<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('template_id')->unsigned();
            $table->boolean('primary')->default(false);
            $table->boolean('authentication')->default(false);
            $table->string('name');
            $table->string('slug')->nullable();
            $table->json('meta')->nullable();
            $table->json('settings')->nullable();
            $table->json('components')->nullable();
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
        Schema::dropIfExists('pages');
    }
}
