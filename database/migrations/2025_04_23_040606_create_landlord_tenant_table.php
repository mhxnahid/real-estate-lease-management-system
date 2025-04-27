<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLandlordTenantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('landlord_tenants', function (Blueprint $table) {
            $table->increments('id'); // Primary key
            $table->unsignedInteger('landlord_id'); // Foreign key to users table
            $table->unsignedInteger('tenant_id'); // Foreign key to properties table
            $table->boolean('accepted_invite')->default(false); // User acceptance status
            $table->boolean('active')->default(false);
            $table->string('invite_token')->nullable(); // Invite token for the tenant
            $table->timestamps(); // Created at and updated at timestamps
            $table->softDeletes();

            // Foreign key constraints
            $table->foreign('landlord_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('tenant_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('landlord_tenants');
    }
}