<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leases', function (Blueprint $table) {
            $table->increments('id'); // Primary key
            $table->unsignedInteger('property_id'); // Foreign key to properties table
            $table->unsignedInteger('tenant_id'); // Foreign key to users table (tenant)
            $table->unsignedInteger('landlord_id'); // Foreign key to users table (landlord)
            $table->string('document_ref')->nullable(); // Reference to uploaded document
            $table->date('lease_start'); // Start date of the lease
            $table->date('lease_end'); // End date of the lease
            $table->boolean('tenant_accepted')->default(false); // Tenant acceptance status
            $table->unsignedInteger('parent_id')->nullable(); // Self-referenced field for renewed lease
            $table->timestamps(); // Created at and updated at timestamps
            $table->softDeletes();
            
            // Foreign key constraints
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->foreign('tenant_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('landlord_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('leases')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leases');
    }
}