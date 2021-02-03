<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableActionInstanceFieldsRenameEventFieldToActionValue extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up()
    {
        $actionInstanceFields = \BristolSU\Support\Action\ActionInstanceField::all();
        foreach ($actionInstanceFields as $actionInstanceField) {
            $actionInstanceField->event_field = sprintf('{{event:%s}}', $actionInstanceField->event_field);
            $actionInstanceField->save();
        }
        
        Schema::table('action_instance_fields', function (Blueprint $table) {
            $table->renameColumn('event_field', 'action_value');
        });
    }

    /**
     * Reverse the migrations.
     *
     */
    public function down()
    {
        Schema::table('action_instance_fields', function (Blueprint $table) {
            $table->renameColumn('action_value', 'event_field');
        });

        $actionInstanceFields = \BristolSU\Support\Action\ActionInstanceField::all();
        foreach ($actionInstanceFields as $actionInstanceField) {
            $actionInstanceField->event_field = substr(substr($actionInstanceField->event_field, 8), 0, -2);
            $actionInstanceField->save();
        }
    }
}
