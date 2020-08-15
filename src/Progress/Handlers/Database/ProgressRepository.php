<?php


namespace BristolSU\Support\Progress\Handlers\Database;


use BristolSU\Support\Progress\Handlers\Database\Models\Progress;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ProgressRepository
{

    /**
     * Get the ID of the most recent progress for each activity instance
     *
     * @return array An array of IDs
     */
    public function recentIds(): array
    {
        return Progress::orderBy('activity_instance_id')
          ->latest('timestamp')
          ->get(['id', 'activity_instance_id'])
          ->unique('activity_instance_id')
          ->pluck('id')
          ->toArray();
    }

    /**
     * Search through the most recent progresses
     *
     * @param array $activityInstanceIds An array of activity instance IDs for which to search
     * @param string $sortBy The key to sort by
     * @param bool $sortDesc Whether the sort direction should be descending
     * @param array $incomplete An array of module instances for which the module instance should be incomplete
     * @param array $complete An array of module instances for which the module instance should be complete
     * @param array $hidden An array of module instances for which the module instance should be hidden
     * @param array $visible An array of module instances for which the module instance should be visible
     * @param array $active An array of module instances for which the module instance should be active
     * @param array $inactive An array of module instances for which the module instance should be inactive
     * @param array $mandatory An array of module instances for which the module instance should be mandatory
     * @param array $optional An array of module instances for which the module instance should be optional
     * @param float|null $progressAbove The lower limit of the percentage through the activity for which to return progresses.
     * @param float|null $progressBelow The upper limit of the percentage through the activity for which to return progresses.
     *
     * @return Collection
     */
    public function searchRecent(
      array $activityInstanceIds,
      string $sortBy = 'percentage',
      bool $sortDesc = false,
      array $incomplete = [],
      array $complete = [],
      array $hidden = [],
      array $visible = [],
      array $active = [],
      array $inactive = [],
      array $mandatory = [],
      array $optional = [],
      float $progressAbove = 0.00,
      float $progressBelow = 100.00
    ): Collection
    {
        return Progress
          ::whereIn('activity_instance_id', $activityInstanceIds)
          ->whereIn('id', $this->recentIds())
          ->with('activityInstance')
          ->with('moduleInstanceProgress')
          ->whereHas('moduleInstanceProgress', function (Builder $query) use (
            $incomplete, $complete, $hidden, $visible, $active, $inactive, $mandatory, $optional
          ) {
              if (count($incomplete) > 0) {
                  $query->where(function(Builder $query) use ($incomplete) {
                      $query->where('complete', false)
                        ->whereIn('module_instance_id', $incomplete);
                  });
              }
              if (count($complete) > 0) {
                  $query->where('complete', true)
                    ->whereIn('module_instance_id', $complete);
              }
              if (count($hidden) > 0) {
                  $query->where('visible', false)
                    ->whereIn('module_instance_id', $hidden);
              }
              if (count($visible) > 0) {
                  $query->where('visible', true)
                    ->whereIn('module_instance_id', $visible);
              }
              if (count($active) > 0) {
                  $query->where('active', true)
                    ->whereIn('module_instance_id', $active);
              }
              if (count($inactive) > 0) {
                  $query->where('active', false)
                    ->whereIn('module_instance_id', $inactive);
              }
              if (count($mandatory) > 0) {
                  $query->where('mandatory', true)
                    ->whereIn('module_instance_id', $mandatory);
              }
              if (count($optional) > 0) {
                  $query->where('mandatory', false)
                    ->whereIn('module_instance_id', $optional);
              }
          })
          ->where('percentage', '>=', $progressAbove)
          ->where('percentage', '<=', $progressBelow)
          ->orderBy($sortBy, ($sortDesc ? 'desc' : 'asc'))
          ->get();
    }

}
