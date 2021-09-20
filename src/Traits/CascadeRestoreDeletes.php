<?php

namespace BristolSU\Support\Traits;

use Dyrynda\Database\Support\CascadeSoftDeleteException;
use Illuminate\Database\Eloquent\Relations\Relation;

trait CascadeRestoreDeletes {

    protected static function bootCascadeRestoreDeletes()
    {
        static::restoring(function($model) {
            $model->validateRestoringRelationships();

            $model->runCascadingRestores();
        });
    }

    protected function validateRestoringRelationships()
    {
        if($invalidCascadingRelationships = $this->hasInvalidCascadingRestoreRelationships()) {
            throw CascadeSoftDeleteException::invalidRelationships($invalidCascadingRelationships);
        }
    }

    protected function hasInvalidCascadingRestoreRelationships()
    {
        return array_filter($this->getCascadingRestores(), function ($relationship) {
            return ! method_exists($this, $relationship) || ! $this->{$relationship}() instanceof Relation;
        });
    }

    protected function getCascadingRestores()
    {
        return isset($this->cascadeDeletes) ? (array) $this->cascadeDeletes : [];
    }

    protected function getActiveCascadingRestores()
    {
        return array_filter($this->getCascadingRestores(), function ($relationship) {
            return ! is_null($this->{$relationship});
        });
    }

    protected function runCascadingRestores()
    {
        foreach ($this->getActiveCascadingRestores() as $relationship) {
            $this->cascadeRestore($relationship);
        }
    }

    protected function cascadeRestore($relationship)
    {
        foreach($this->{$relationship}()->withTrashed()->where('deleted_at', '>=', $this->deleted_at)->get() as $model) {
            $model->restore();
        }
    }
}
