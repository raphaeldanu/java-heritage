<?php

use App\Models\Employee;
use App\Models\Ptkp;
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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Employee::class)->constrained();
            $table->foreignIdFor(Ptkp::class)->constrained();
            $table->date('month_and_year');
            $table->tinyInteger('actual_workdays');
            $table->decimal('basic_salary', 16, 2);
            $table->decimal('service', 16, 2);
            $table->decimal('jkk', 16, 2);
            $table->decimal('jkm', 16, 2);
            $table->decimal('bpjs', 16, 2);
            $table->decimal('gross_salary', 16, 2);
            $table->decimal('thr', 16, 2);
            $table->decimal('year_gross_salary', 16, 2);
            $table->decimal('position_allowance', 16, 2);
            $table->decimal('jht_one_year', 16, 2);
            $table->decimal('pension_one_year', 16, 2);
            $table->decimal('netto', 16, 2);
            $table->decimal('pkp', 16, 2);
            $table->decimal('pph', 16, 2);
            $table->decimal('monthly_pph', 16, 2);
            $table->decimal('cug_cut', 16, 2);
            $table->decimal('salary_received', 16, 2);
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
        Schema::dropIfExists('salaries');
    }
};
