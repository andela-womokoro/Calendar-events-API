<?php

namespace App\Repositories;

abstract class BaseRepository
{
    abstract function create($data);

    abstract function fetchMany($userId, $begin, $perPage, $sortBy, $sortDirection);

    abstract function fetchOne($userId, $resourceId);

    abstract function update($data);

    abstract function delete($userId, $resourceId);
}