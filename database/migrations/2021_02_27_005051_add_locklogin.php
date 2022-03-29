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
            if (!Schema::hasColumn('users', 'Player')) {
                $table->string('Player')->nullable();
            }
            if (!Schema::hasColumn('users', 'UUID')) {
                $table->string('UUID')->nullable();
            }
            if (!Schema::hasColumn('users', 'Password')) {
                $table->string('Password')->nullable();
            }
            if (!Schema::hasColumn('users', 'Auth')) {
                $table->smallInteger('Auth')->default(0);
            }
            if (!Schema::hasColumn('users', 'Token')) {
                $table->string('Token')->nullable();
            }
            if (!Schema::hasColumn('users', 'Pin')) {
                $table->smallInteger('Pin')->nullable();
            }
            if (!Schema::hasColumn('users', 'created_at')) {
                $table->string('created_at')->nullable();
            }
        });
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
