<?php


namespace BristolSU\Support\Progress\Handlers\Database;

use BristolSU\Support\Progress\Handlers\Database\Models\Progress;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ProgressRepository
{
    /**
     * Get the ID of the most recent progress for each activity instance.
     *
     * @return array An array of IDs
     */
    public function recentIds(): array
    {
        return Progress::orderBy('activity_instance_id')
            ->orderBy('id', 'desc')
            ->latest('timestamp')
            ->get(['id', 'activity_instance_id'])
            ->unique('activity_instance_id')
            ->pluck('id')
            ->toArray();
    }

    /**
     * Search through the most recent progresses.
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
    ): Collection {
        return Progress
          ::whereIn('activity_instance_id', $activityInstanceIds)
              ->whereIn('id', $this->recentIds())
              ->with('activityInstance')
              ->with('moduleInstanceProgress')
              ->when(
                  (count($incomplete) + count($complete) + count($hidden) + count($visible) + count($active) + count($inactive) + count($mandatory) + count($optional)) > 0,
                  function (Builder $query) use (
                      $incomplete,
                      $complete,
                      $hidden,
                      $visible,
                      $active,
                      $inactive,
                      $mandatory,
                      $optional
                  ) {
                      $this->whereModuleInstanceExistsQuery($query, $incomplete, 'complete', false);
                      $this->whereModuleInstanceExistsQuery($query, $complete, 'complete', true);
                      $this->whereModuleInstanceExistsQuery($query, $hidden, 'visible', false);
                      $this->whereModuleInstanceExistsQuery($query, $visible, 'visible', true);
                      $this->whereModuleInstanceExistsQuery($query, $inactive, 'active', false);
                      $this->whereModuleInstanceExistsQuery($query, $active, 'active', true);
                      $this->whereModuleInstanceExistsQuery($query, $optional, 'mandatory', false);
                      $this->whereModuleInstanceExistsQuery($query, $mandatory, 'mandatory', true);
                  }
              )
              ->where('percentage', '>=', $progressAbove)
              ->where('percentage', '<=', $progressBelow)
              ->orderBy($sortBy, ($sortDesc ? 'desc' : 'asc'))
              ->get();
    }

    /**
     * Construct a where query based around module instance progress statuses.
     *
     * @param Builder $query The query to modidy
     * @param array $ids A list of IDs of modules that should meet the conditoin
     * @param string $attributeName The name of the attribute in the database
     * @param bool $attributeValue The value of the attribute in the database
     */
    private function whereModuleInstanceExistsQuery(Builder &$query, array $ids, string $attributeName, bool $attributeValue)
    {
        foreach ($ids as $id) {
            $query->whereHas('moduleInstanceProgress', function (Builder $query) use ($id, $attributeName, $attributeValue) {
                $query->where([$attributeName => $attributeValue, 'module_instance_id' => $id]);
            });
        }
    }

    /**
     * Get all progress models for the given activity instance.
     *
     * @param int $activityInstanceId
     * @return Collection
     */
    public function allForActivityInstance(int $activityInstanceId)
    {
        return Progress::where('activity_instance_id', $activityInstanceId)
            ->orderBy('timestamp', 'asc')
            ->with('activityInstance')
            ->with('moduleInstanceProgress')
            ->get();
    }
}
