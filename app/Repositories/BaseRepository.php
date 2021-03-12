<?php

namespace App\Repositories;

abstract class BaseRepository
{
    abstract function create($data);

    abstract function fetchMany($begin, $perPage, $sortBy, $sortDirection);

    abstract function fetchOne($id);

    abstract function update($data, $id);

    abstract function delete($id);
}