<?php

namespace App\Contracts\Actions;

interface ActionContract
{
    /**
     * Execute the action.
     */
    public function execute(mixed $input): mixed;
}
