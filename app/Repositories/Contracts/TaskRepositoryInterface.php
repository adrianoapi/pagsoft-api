<?php

namespace App\Repositories\Contracts;

interface TaskRepositoryInterface
{
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);
    public function findById(int $id);
}
