<?php

use App\Models\TrainingMenu;
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
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(TrainingMenu::class)->constrained();
            $table->string('trainers_name');
            $table->date('training_date');
            $table->string('training_venue');
            $table->integer('training_length');
            $table->integer('attendants')->default(0);
            $table->decimal('cost_per_participant', 16, 2)->default(0);
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
        Schema::dropIfExists('trainings');
    }
};
