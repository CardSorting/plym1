<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->json('mana_cost')->nullable()->after('name');
            $table->string('card_type')->nullable()->after('description');
            $table->text('abilities')->nullable()->after('card_type');
            $table->text('flavor_text')->nullable()->after('abilities');
            $table->string('power_toughness')->nullable()->after('flavor_text');
            $table->string('set_name')->default('AI')->after('power_toughness');
            $table->string('card_number')->nullable()->after('set_name');
        });
    }

    public function down(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->dropColumn([
                'mana_cost',
                'card_type',
                'abilities',
                'flavor_text',
                'power_toughness',
                'set_name',
                'card_number'
            ]);
        });
    }
};
