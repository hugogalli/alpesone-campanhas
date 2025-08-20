<?php namespace Alpes\Campaigns\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class CreateCampaignsTable extends Migration
{
    public function up()
    {
        Schema::create('alpes_campaigns', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('section1')->nullable();
            $table->json('section2')->nullable();
            $table->json('section3')->nullable();
            $table->json('footer')->nullable();
            $table->json('brand')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('alpes_campaigns');
    }
}
