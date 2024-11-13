<?php

// database/migrations/xxxx_xx_xx_create_code_subject_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCodeSubjectTable extends Migration
{
    public function up()
    {
        Schema::create('code_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('code_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('code_subject');
    }
}
