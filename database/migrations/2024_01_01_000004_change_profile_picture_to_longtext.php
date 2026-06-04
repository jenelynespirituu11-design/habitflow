<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE users MODIFY profile_picture LONGTEXT NULL');
    }

    public function down()
    {
        DB::statement('ALTER TABLE users MODIFY profile_picture VARCHAR(255) NULL');
    }
};
