<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Enums\UserRole;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $roles = array_map(fn (UserRole $elm) => $elm->value, UserRole::cases());
        
        Schema::table('users', function (Blueprint $table) use ($roles) {
            $table->enum('role', $roles)->default('regular');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
