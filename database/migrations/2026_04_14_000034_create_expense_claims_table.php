<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This table handles HRMS reimbursements for field agents and staff.
     */
    public function up(): void
    {
        Schema::create('expense_claims', function (Blueprint $table) {
            $table->id();
            
            // The Employee making the claim
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            
            // Expense Details
            $table->string('title'); // e.g., "Fuel for Goregaon Site Visit"
            $table->enum('category', [
                'Fuel/Conveyance', 
                'Client Refreshments', 
                'Marketing/Flyers', 
                'Communication/Internet',
                'Other'
            ]);
            
            $table->decimal('amount', 15, 2);
            $table->date('expense_date');
            
            // Proof of Expense
            $table->string('receipt_path')->nullable(); // Path to the uploaded bill/photo
            
            // Approval Workflow
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Paid'])->default('Pending');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            
            // Payment Details
            $table->string('payment_reference')->nullable(); // UTR/Transaction ID for the reimbursement
            $table->text('rejection_reason')->nullable();
            
            // Linking to a specific lead or project (Optional)
            $table->foreignId('lead_id')->nullable()->constrained()->onDelete('set null');
            
            $table->timestamps();
            $table->softDeletes();

            // Indexing for HRMS reports
            $table->index(['employee_id', 'status', 'expense_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_claims');
    }
};