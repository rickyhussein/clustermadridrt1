<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('type')->nullable();
            $table->bigInteger('nominal')->nullable();
            $table->text('notes')->nullable();
            $table->date('expired_date')->nullable();
            $table->string('status')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->string('in_out')->nullable();

            $table->string('redirect_url')->nullable();
            $table->string('trx_id')->nullable();
            $table->string('payment_reff')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->string('payment_status_code')->nullable();
            $table->string('payment_status_desc')->nullable();
            $table->string('payment_channel_uid')->nullable();
            $table->string('payment_channel')->nullable();
            $table->string('signature')->nullable();

            $table->string('file_transaction_path')->nullable();
            $table->string('file_transaction_name')->nullable();

            $table->string('status_approval')->nullable();

            $table->bigInteger('qty')->nullable();
            $table->string('status_gate_card')->nullable();
            $table->string('status_gate_card_text')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->string('vehicle_type_text')->nullable();

            $table->unsignedBigInteger('approved_by')->nullable();
            $table->foreign('approved_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->text('approver_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
