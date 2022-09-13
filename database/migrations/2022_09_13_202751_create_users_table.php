<?php

use App\Core\Base\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends BaseMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(string $tableName='users')
    {
        parent::up($tableName);

        Schema::table($tableName, function (Blueprint $table) {
            $table->string('name', 100);
            $table->string('username', 50)->unique();
            $table->string('password', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
