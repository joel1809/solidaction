<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->unsignedTinyInteger('focus_x')->nullable()->after('image');
            $table->unsignedTinyInteger('focus_y')->nullable()->after('focus_x');
        });
    }

    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->dropColumn(['focus_x', 'focus_y']);
        });
    }
};
