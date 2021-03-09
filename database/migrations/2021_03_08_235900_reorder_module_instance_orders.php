<?php

use BristolSU\Support\Activity\Contracts\Repository as ActivityRepository;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstanceRepository as ModuleInstanceRepositoryContract;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Database\Migrations\Migration;

class ReorderModuleInstanceOrders extends Migration
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
        foreach ($this->activityRepository->all() as $activity) {
            ModuleInstance::setNewOrder(
                ModuleInstance::where('activity_id', $activity->id)->orderBy('order')->pluck('id')
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     */
    public function down()
    {
    }
}
