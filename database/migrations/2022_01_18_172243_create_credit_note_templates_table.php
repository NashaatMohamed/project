<?php

use App\Models\CreditNoteTemplate;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditNoteTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_note_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('view');
            $table->string('path');
            $table->timestamps();
        });

        CreditNoteTemplate::create([
            'name' => 'Template 1',
            'view' => 'template_1',
            'path' => '/assets/images/templates/credit_note/template_1.png'
        ]);

        CreditNoteTemplate::create([
            'name' => 'Template 2',
            'view' => 'template_2',
            'path' => '/assets/images/templates/credit_note/template_2.png'
        ]);

        CreditNoteTemplate::create([
            'name' => 'Template 3',
            'view' => 'template_3',
            'path' => '/assets/images/templates/credit_note/template_3.png'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credit_note_templates');
    }
}
