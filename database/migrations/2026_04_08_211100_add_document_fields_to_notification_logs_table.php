<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('notification_logs', function (Blueprint $table): void {
            $table->foreignId('master_document_id')->nullable()->after('customer_id')->constrained('master_documents')->nullOnDelete();
            $table->string('document_title')->nullable()->after('message');
            $table->string('document_url')->nullable()->after('document_title');
        });
    }

    public function down(): void
    {
        Schema::table('notification_logs', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('master_document_id');
            $table->dropColumn(['document_title', 'document_url']);
        });
    }
};
