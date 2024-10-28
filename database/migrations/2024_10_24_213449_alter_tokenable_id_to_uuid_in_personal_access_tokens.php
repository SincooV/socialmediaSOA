<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AlterTokenableIdToUuidInPersonalAccessTokens extends Migration
{
    public function up()
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            // Cria uma nova coluna temporária com UUID
            $table->uuid('tokenable_id_uuid')->nullable();
        });

        // Copia os dados da coluna antiga para a nova coluna
        DB::statement('UPDATE personal_access_tokens SET tokenable_id_uuid = uuid_generate_v4()');

        // Remove a coluna antiga e renomeia a nova coluna
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->dropColumn('tokenable_id');  // Remove a coluna antiga
            $table->renameColumn('tokenable_id_uuid', 'tokenable_id');  // Renomeia a nova coluna
        });
    }

    public function down()
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            // Reverte as alterações, voltando para bigint
            $table->bigInteger('tokenable_id')->nullable(false)->change();
        });
    }
}
