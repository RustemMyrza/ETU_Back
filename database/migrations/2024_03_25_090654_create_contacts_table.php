<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('tab_name')->nullable();
            $table->string('address');
            $table->string('admissions_committee_num_1');
            $table->string('admissions_committee_num_2');
            $table->string('admissions_committee_mail');
            $table->string('rectors_reception_num');
            $table->string('office_receptionist_num');
            $table->string('tiktok_name');
            $table->text('tiktok_link');
            $table->string('instagram_name');
            $table->text('instagram_link');
            $table->text('facebook_link');
            $table->text('youtube_link');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
