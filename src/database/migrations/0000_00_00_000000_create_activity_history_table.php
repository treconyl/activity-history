<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_history', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('modal')->comment('model');
            $table->unsignedBigInteger('modal_id')->comment('Id of the operation model');

            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('role_id')->default(0);

            $table->string('action', 10)->nullable();

            $table->text('comment')->nullable()->comment('comment of the action');
            $table->mediumText('updated_fields')->nullable();
            $table->mediumText('original_fields')->nullable();
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
        Schema::dropIfExists('activity_history');
    }
}