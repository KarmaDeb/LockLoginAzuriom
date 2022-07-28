<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLockLogin extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'player')) {
                $table->string('player')->nullable();
            }
            if (!Schema::hasColumn('users', 'uuid')) {
                $table->string('uuid')->nullable();
            }
            if (!Schema::hasColumn('users', 'password')) {
                $table->string('password')->nullable();
            }
            if (!Schema::hasColumn('users', 'Auth')) {
                $table->smallInteger('auth')->default(0);
            }
            if (!Schema::hasColumn('users', 'Token')) {
                $table->string('token')->nullable();
            }
            if (!Schema::hasColumn('users', 'Pin')) {
                $table->smallInteger('pint')->nullable();
            }
            if (!Schema::hasColumn('users', 'panic')) {
                $table->string('panic')->nullable();
            }
            if (!Schema::hasColumn('users', 'created_at')) {
                $table->string('created_at')->nullable();
            }
        });
        
        //Not tested yet, do not use on a production server until this comment gets removed
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            DB::table('users')->where('name', '=', $user->name)->update(['Player' => $user->name]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['PLAYER', 'Auth', 'Token', 'Pin']);
        });
    }
}
