<?php

use App\Models\Position;
use App\Models\SalaryRange;
use App\Models\User;
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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Position::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(SalaryRange::class)->nullable()->constrained()->nullOnDelete();
            $table->string('nik', 16)->unique();
            $table->string('nip', 7)->unique();
            $table->string('bpjs_tk_number', 15);
            $table->string('bpjs_kes_number', 15);
            $table->string('npwp_number', 16);
            $table->string('name', 200);
            $table->string('employment_status');
            $table->date('first_join');
            $table->date('last_contract_start');
            $table->date('last_contract_end');
            $table->string('birth_place', 100);
            $table->date('birth_date');
            $table->string('gender', 6);
            $table->string('tax_status', 4);
            $table->text('address_on_id');
            $table->string('phone_number', 16);
            $table->string('blood_type')->nullable();
            $table->timestamp('resign_date')->nullable();
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
        Schema::dropIfExists('employees');
    }
};
