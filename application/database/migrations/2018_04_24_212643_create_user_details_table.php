<?php

use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->string('company')->nullable();
            $table->enum('title', User::TITLES)->nullable();
            $table->enum('salutation', ['male', 'female']);
            $table->string('birth_date');
            $table->string('birth_place');
            $table->string('address_street')->nullable();
            $table->string('address_number')->nullable();
            $table->string('address_addition')->nullable();
            $table->string('address_zipcode')->nullable();
            $table->string('address_city')->nullable();
            $table->string('phone');
            $table->string('website')->nullable();
            $table->text('vat_id')->nullable();
            $table->string('tax_office')->nullable();
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
        Schema::dropIfExists('user_details');
    }
}
