<?php

namespace App\Repositories\Contracts;

interface CollectionItemRepositoryInterface
{
    public function delete(int $id);

    public function update(array $data, int $id);

    public function store(array $data);

    public function findAll();

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);
}
