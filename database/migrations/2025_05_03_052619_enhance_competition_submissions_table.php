<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnhanceCompetitionSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('competition_submissions', function (Blueprint $table) {
            $table->string('original_filename')->nullable()->after('file_path');
            $table->foreignId('evaluated_by')->nullable()->after('feedback')->constrained('users');
            $table->timestamp('evaluated_at')->nullable()->after('evaluated_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('competition_submissions', function (Blueprint $table) {
            $table->dropForeign(['evaluated_by']);
            $table->dropColumn(['original_filename', 'evaluated_by', 'evaluated_at']);
        });
    }
}