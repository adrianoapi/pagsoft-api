<?php

namespace App\Repositories\Contracts;

interface CollectionItemImageRepositoryInterface
{
    public function delete(int $id);

    public function store(array $data);

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);
}
