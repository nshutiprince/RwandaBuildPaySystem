<?php

use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddsRoleIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('role_id')->default(Role::IS_USER)->after('id');
            $table->integer('loyalty_points')->default(0)->after('password');
            $table->boolean('is_member')->default(false)->after('loyalty_points');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            Schema::dropColumns('role_id');
            Schema::dropColumns('loyalty_points');
            Schema::dropColumns('is_member');
        });
    }
}
