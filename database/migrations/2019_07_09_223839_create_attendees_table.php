<?php

use App\Enums\AttendeeType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->tinyInteger('type')->default(AttendeeType::ATTENDEE);
            $table->string('name');
            $table->string('mobile');
            $table->string('email')->unique();
            $table->string('profession')->nullable();
            $table->text('social_profile_url')->nullable();
            $table->boolean('is_paid')->nullable()->default(false);
            $table->tinyInteger('status')->default(1);
            $table->json('misc')->nullable();
            $table->timestamp('attend_at')->nullable();
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
        Schema::dropIfExists('attendees');
    }
}
