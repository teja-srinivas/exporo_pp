<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class InitialMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', static function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id')->nullable()->index();
            $table->text('first_name');
            $table->text('last_name');
            $table->string('email')->index();
            $table->string('password');
            $table->unsignedInteger('parent_id')->index()->default(0);
            $table->string('api_token')->index();
            $table->rememberToken();
            $table->timestamps();
            $table->unique(['email', 'company_id']);
            $table->dateTime('accepted_at')->nullable();
            $table->dateTime('rejected_at')->nullable();
        });

        Schema::create('password_resets', static function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('agbs', static function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->index()->nullable();
            $table->string('name');
            $table->string('filename')->nullable();
            $table->boolean('is_default')->default(true);
            $table->timestamps();
        });

        Schema::create('agb_user', static function (Blueprint $table) {
            $table->unsignedInteger('agb_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();

            $table->foreign('agb_id')
                ->references('id')
                ->on('agbs')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->primary(['agb_id', 'user_id']);
        });

        Schema::create('companies', static function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('user_details', static function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->string('company')->nullable();
            $table->enum('title', User::TITLES)->nullable();
            $table->enum('salutation', ['male', 'female'])->nullable();
            $table->date('birth_date')->nullable();
            $table->text('birth_place')->nullable();
            $table->text('address_street')->nullable();
            $table->text('address_number')->nullable();
            $table->text('address_addition')->nullable();
            $table->text('address_zipcode')->nullable();
            $table->text('address_city')->nullable();
            $table->text('phone')->nullable();
            $table->text('website')->nullable();
            $table->text('vat_id')->nullable();
            $table->text('tax_office')->nullable();
            $table->timestamps();
            $table->decimal('vat_amount')->default(19);
            $table->boolean('vat_included')->default(true);
            $table->decimal('registration_bonus')->default(0);
            $table->decimal('first_investment_bonus')->default(0);
            $table->decimal('further_investment_bonus')->default(0);
        });

        $tableNames = config('permission.table_names');

        Schema::create($tableNames['permissions'], static function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create($tableNames['roles'], static function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create($tableNames['model_has_permissions'], static function (Blueprint $table) use ($tableNames) {
            $table->unsignedInteger('permission_id');
            $table->morphs('model');
            $table->timestamps();

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->primary(['permission_id', 'model_id', 'model_type']);
        });

        Schema::create($tableNames['model_has_roles'], static function (Blueprint $table) use ($tableNames) {
            $table->unsignedInteger('role_id');
            $table->morphs('model');
            $table->timestamps();

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['role_id', 'model_id', 'model_type']);
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedInteger('permission_id');
            $table->unsignedInteger('role_id');
            $table->timestamps();

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);

            $this->clearPermissionCache();
        });

        Schema::create('audits', static function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('event');
            $table->morphs('auditable');
            $table->text('old_values')->nullable();
            $table->text('new_values')->nullable();
            $table->text('url')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('tags')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('documents', static function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index();
            $table->string('filename');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('investors', static function (Blueprint $table) {
            $table->unsignedInteger('id')->primary()->nullable();
            $table->text('first_name');
            $table->text('last_name');
            $table->timestamps();
            $table->unsignedInteger('user_id')->nullable()->index();
            $table->date('claim_end')->nullable();
            $table->date('activation_at')->nullable();
        });

        Schema::create('projects', static function (Blueprint $table) {
            $table->unsignedInteger('id')->comment('External Project ID');
            $table->string('name');
            $table->decimal('interest_rate')->nullable();
            $table->decimal('margin')->nullable();
            $table->string('type');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('schema_id')->nullable()->index();
            $table->timestamps();
            $table->decimal('capital_cost')->nullable();
            $table->date('launched_at')->nullable();
            $table->date('payback_min_at')->nullable();
            $table->date('payback_max_at')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->unsignedInteger('approved_by')->index()->nullable();
        });

        Schema::create('investments', static function (Blueprint $table) {
            $table->unsignedInteger('id')->primary()->nullable();
            $table->unsignedInteger('investor_id')->nullable()->index();
            $table->unsignedInteger('project_id')->nullable()->index();
            $table->unsignedInteger('amount')->nullable();
            $table->decimal('interest_rate')->default(1);
            $table->unsignedInteger('bonus')->nullable();
            $table->string('type')->nullable();
            $table->dateTime('paid_at')->index()->nullable();
            $table->boolean('is_first_investment')->nullable();
            $table->dateTime('acknowledged_at')->index()->nullable();
            $table->dateTime('cancelled_at')->index()->nullable();
            $table->timestamps();
        });

        Schema::create('bills', static function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index();
            $table->date('released_at')->nullable();
            $table->timestamps();
        });

        Schema::create('commissions', static function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('bill_id')->index()->nullable();
            $table->nullableMorphs('model');
            $table->unsignedInteger('user_id')->comment('ID of the internal Partner user');
            $table->decimal('net');
            $table->decimal('gross');
            $table->string('note_private')->nullable()->comment('Internal memo');
            $table->string('note_public')->nullable()->comment('Shown on the Bill to the user');
            $table->dateTime('reviewed_at')->nullable()->comment('markes this as "valid"');
            $table->unsignedInteger('reviewed_by')->nullable();
            $table->dateTime('rejected_at')->nullable();
            $table->unsignedInteger('rejected_by')->index()->nullable();
            $table->boolean('on_hold')->index()->default(false)
                ->comment('don\'t put this on the bill, ask next time');

            $table->timestamps();
        });

        Schema::create('schemas', static function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('formula');
            $table->timestamps();
        });
    }
}
