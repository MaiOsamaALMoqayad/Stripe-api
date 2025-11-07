<?php
// database/migrations/YYYY_MM_DD_create_payments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('stripe_session_id')->unique(); // مُعرّف الجلسة الخاص بـ Stripe
            $table->string('transaction_id')->nullable();  // مُعرّف المعاملة النهائي (من Stripe)
            $table->unsignedBigInteger('user_id')->nullable(); // ربط الدفع بالمستخدم (اختياري)
            $table->decimal('amount', 8, 2); // قيمة المبلغ المدفوع
            $table->string('currency', 3); // العملة
            $table->string('status')->default('pending'); // حالة الدفع: (pending, completed, failed)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
