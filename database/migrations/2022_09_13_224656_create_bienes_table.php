<?php

use App\Core\Base\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBienesTable extends BaseMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(string $tableName='Bienes')
    {
        parent::up($tableName);

        Schema::table($tableName, function (Blueprint $table) {
            $table->string('articulo', 255);
            $table->string('descripcion', 255);
            $table->unsignedBigInteger("usuario_id");
            #$table->foreign("usuario_id")->reference("id")->on("users");
        });

        Schema::table($tableName, function(Blueprint $table) {
            $table->foreign('usuario_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Bienes');
    }
}
