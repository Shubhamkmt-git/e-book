<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('sample_file')->nullable()->after('cover_image');
            $table->string('ebook_file')->nullable()->after('sample_file');
            $table->integer('pages')->nullable()->after('ebook_file');
            $table->string('language')->default('English')->nullable()->after('pages');
            $table->string('format')->default('PDF')->nullable()->after('language');
            $table->string('file_size')->nullable()->after('format');
            $table->string('meta_title')->nullable()->after('file_size');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('meta_keywords')->nullable()->after('meta_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn([
                'sample_file',
                'ebook_file',
                'pages',
                'language',
                'format',
                'file_size',
                'meta_title',
                'meta_description',
                'meta_keywords',
            ]);
        });
    }
};
