<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Role;
use App\User;

class InitialMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id')->nullable()->index();
            $table->string('first_name', 350);
            $table->string('last_name', 350);
            $table->string('email', 350)->index();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->unique(['email', 'company_id']);
            $table->dateTime('accepted_at')->nullable();
            $table->dateTime('rejected_at')->nullable();
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('agbs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->index()->nullable();
            $table->string('name');
            $table->string('filename')->nullable();
            $table->boolean('is_default')->default(true);
            $table->timestamps();
        });

        Schema::create('agb_user', function (Blueprint $table) {
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

        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('user_details', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->string('company')->nullable();
            $table->enum('title', User::TITLES)->nullable();
            $table->enum('salutation', ['male', 'female'])->nullable();
            $table->date('birth_date')->nullable();
            $table->string('birth_place', 350)->nullable();
            $table->string('address_street', 350)->nullable();
            $table->string('address_number', 350)->nullable();
            $table->string('address_addition', 350)->nullable();
            $table->string('address_zipcode', 350)->nullable();
            $table->string('address_city', 350)->nullable();
            $table->string('phone', 350)->nullable();
            $table->string('website', 350)->nullable();
            $table->string('vat_id', 350)->nullable();
            $table->string('tax_office', 350)->nullable();
            $table->timestamps();
            $table->decimal('vat_amount')->default(19);
            $table->boolean('vat_included')->default(true);
            $table->unsignedInteger('parent_id')->index()->default(0);
            $table->decimal('registration_bonus')->default(0);
            $table->decimal('first_investment_bonus')->default(0);
            $table->decimal('further_investment_bonus')->default(0);
        });

        $tableNames = config('permission.table_names');

        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create($tableNames['roles'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedInteger('permission_id');
            $table->morphs('model');
            $table->timestamps();

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->primary(['permission_id', 'model_id', 'model_type']);
        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames) {
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

            app(PermissionRegistrar::class)->forgetCachedPermissions();
        });



        Schema::create('audits', function (Blueprint $table) {
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

        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index();
            $table->string('filename');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();

        });

        Schema::create('investors', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary()->nullable();
            $table->string('first_name', 300);
            $table->string('last_name', 300);
            $table->timestamps();
            $table->unsignedInteger('user_id')->nullable()->index();
            $table->date('claim_end')->nullable();
            $table->date('activation_at')->nullable();
        });

        Schema::create('projects', function (Blueprint $table) {
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

        Schema::create('investments', function (Blueprint $table) {
            $table->unsignedInteger('id')->nullable();
            $table->unsignedInteger('investor_id')->nullable()->index();
            $table->unsignedInteger('ext_user_id')->nullable();
            $table->unsignedInteger('project_id')->nullable()->index();
            $table->unsignedInteger('investsum')->nullable();
            $table->decimal('interest_rate')->default(1);
            $table->unsignedInteger('bonus')->nullable();
            $table->string('type')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->boolean('is_first_investment')->nullable();
            $table->dateTime('acknowledged_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->timestamps();
        });


        Schema::create('bills', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index();
            $table->date('released_at')->nullable();
            $table->timestamps();
        });

        Schema::create('commissions', function (Blueprint $table) {
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

        Schema::create('schemas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('formula');
            $table->timestamps();
        });

        Role::create(['name' => Role::ADMIN]);
        Role::create(['name' => Role::PARTNER]);
        Permission::create(['name' => 'manage agbs']);
        Permission::create(['name' => 'manage documents']);
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'manage authorization']);
        Permission::create(['name' => 'process partners']);

        Role::findByName(Role::ADMIN)->givePermissionTo(
            Permission::create(['name' => 'view audits'])
        );

        Role::findByName(Role::PARTNER)->givePermissionTo(
            Permission::create(['name' => 'view partner dashboard'])
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('agbs');
        Schema::dropIfExists('agb_user');
        Schema::dropIfExists('companies');
        Schema::dropIfExists('user_details');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('investments');

        $tableNames = config('permission.table_names');

        Schema::drop($tableNames['role_has_permissions']);
        Schema::drop($tableNames['model_has_roles']);
        Schema::drop($tableNames['model_has_permissions']);
        Schema::drop($tableNames['roles']);
        Schema::drop($tableNames['permissions']);
        Schema::dropIfExists('bills');
        Schema::dropIfExists('commissions');
    }
}
