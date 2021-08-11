<?php

use BristolSU\Support\Activity\Contracts\Repository as ActivityRepository;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstanceRepository as ModuleInstanceRepositoryContract;
use BristolSU\Support\ModuleInstance\ModuleInstanceGrouping;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActivityIdToModuleInstanceGrouping extends Migration
{
    private ActivityRepository $activityRepository;

    private ModuleInstanceRepositoryContract $moduleInstanceRepository;

    public function __construct()
    {
        $this->activityRepository = app(ActivityRepository::class);
        $this->moduleInstanceRepository = app(ModuleInstanceRepositoryContract::class);
    }

    /**
     * Run the migrations.
     *
     */
    public function up()
    {
        Schema::table('module_instance_grouping', function (Blueprint $table) {
            $table->unsignedBigInteger('activity_id')->nullable();
        });

        foreach ($this->activityRepository->all() as $activity) {
            $groupingIds = collect();
            foreach ($this->moduleInstanceRepository->allThroughActivity($activity) as $moduleInstance) {
                if ($moduleInstance->grouping_id === null) {
                    continue;
                }
                $groupingIds->push($moduleInstance->grouping_id);
            }
            \Illuminate\Support\Facades\DB::table('module_instance_grouping')
                ->whereIn('id', $groupingIds->unique())
                ->update([
                    'activity_id' => $activity->id
                ]);
            ModuleInstanceGrouping::setNewOrder(
                ModuleInstanceGrouping::forActivity($activity)->orderBy('order')->pluck('id')
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     */
    public function down()
    {
        Schema::table('module_instance_grouping', function (Blueprint $table) {
            $table->dropColumn('activity_id');
        });
    }
}
