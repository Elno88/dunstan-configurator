<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('external_id')->index();
            $table->string('ssn')->nullable()->index();
            $table->string('name')->nullable();
            $table->string('animal_name')->nullable();
            $table->string('animal_breed')->nullable();
            $table->string('animal_gender')->nullable();
            $table->string('animal_chip_number')->nullable();
            $table->date('animal_birth')->nullable();
            $table->unsignedInteger('animal_price')->default(0);
            $table->string('insurance_company')->nullable();
            $table->string('insurance_name')->nullable();
            $table->string('insurance_type')->nullable();
            $table->string('insurance_sub_type')->nullable();
            $table->string('insurance_number')->nullable();
            $table->unsignedTinyInteger('premium_frequency')->default(12);
            $table->unsignedInteger('premium_amount')->default(0);
            $table->string('premium_method')->nullable();
            $table->unsignedInteger('veterinary_amount')->default(0);
            $table->unsignedInteger('veterinary_remaining')->default(0);
            $table->boolean('coming')->default(0);
            $table->boolean('employer_paid')->default(0);
            $table->string('other')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('renewal_at')->nullable();
            $table->timestamp('exported_at')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leads');
    }
}
