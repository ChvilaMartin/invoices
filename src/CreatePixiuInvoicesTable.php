<?php namespace Pixiucz\Invoices;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePixiuInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pixiu_invoices', function (Blueprint $table) {
            $table->string('name')->unique();
            $table->string('pattern');
            $table->integer('invoice_number')->unsigned();
            $table->integer('actual_year')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pixiu_invoices');
    }
}
