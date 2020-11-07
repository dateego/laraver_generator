<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateTestUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_users', function ($table) {
            $table->increments('id');
            $table->string('email');
            $table->string('password');

            $table->timestamps();
        });

        $now = Carbon::now();

        DB::table('test_users')->insert([
            'email' => 'hello@orchestraplatform.com',
            'password' => Hash::make('123'),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('testbench_users');
    }
}
