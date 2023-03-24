<?php

namespace App\Repositories\Contracts;

interface CollectionSharingRepositoryInterface
{
    public function delete(int $id);

    public function store(int $id, array $data);

    public function findAll();

}
