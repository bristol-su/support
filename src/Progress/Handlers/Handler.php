<?php


namespace BristolSU\Support\Progress\Handlers;


use BristolSU\Support\Progress\Progress;

interface Handler
{

    /**
     * Save many progresses
     * 
     * @param array|Progress[] $progresses
     */
    public function saveMany(array $progresses): void;

    /**
     * Save a single progress
     * 
     * @param Progress $progress
     */
    public function save(Progress $progress): void;
    
}