<?php

namespace Modules\Laralite\Repositories;

interface RepositoryInterface
{
    public function find();

    public function firstWhere();

    public function findWhere();
}