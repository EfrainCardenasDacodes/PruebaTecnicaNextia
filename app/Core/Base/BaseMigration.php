<?php

namespace App\Core\Base;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BaseMigration extends Migration {

  public function up(string $tableName) {
    Schema::create($tableName, function (Blueprint $table) {
      $table->id();
      $table->timestamps();
    });
  }
}