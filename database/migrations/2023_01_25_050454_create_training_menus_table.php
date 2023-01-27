<?php

use App\Models\Department;
use App\Models\TrainingSubject;
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
        Schema::create('training_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(TrainingSubject::class)->constrained();
            $table->foreignIdFor(Department::class)->nullable()->constrained()->nullOnDelete();
            $table->string('title');
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
        Schema::dropIfExists('training_menus');
    }
};
