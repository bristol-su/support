<?php

namespace BristolSU\Support\Eloquent;

use BristolSU\Support\Eloquent\Exceptions\CascadeRestoreDeletesException;
use Illuminate\Database\Eloquent\Relations\Relation;

trait CascadeRestoreDeletes
{
    protected static function bootCascadeRestoreDeletes()
    {
        static::restoring(function ($model) {
            $model->validateRestoringRelationships();

            $model->runCascadingRestores();
        });
    }

    protected function validateRestoringRelationships()
    {
        if (! method_exists($this, 'runSoftDelete')) {
            throw CascadeRestoreDeletesException::softDeleteNotImplemented(get_called_class());
        }

        if ($invalidCascadingRelationships = $this->hasInvalidCascadingRestoreRelationships()) {
            throw CascadeRestoreDeletesException::invalidRelationships($invalidCascadingRelationships);
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
        foreach ($this->{$relationship}()->withTrashed()->where('deleted_at', '>=', $this->deleted_at)->get() as $model) {
            $model->restore();
        }
    }
}
