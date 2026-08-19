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
        Schema::create('webauthn_credentials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name')->comment('Nickname entered by user e.g. MacBook TouchID');
            $table->text('credential_id');
            $table->text('public_key');
            $table->string('attestation_format')->default('none');
            $table->unsignedInteger('sign_count')->default(0);
            $table->string('user_handle')->nullable();
            $table->timestamps();

            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webauthn_credentials');
    }
};
