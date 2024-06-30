<?php

namespace App\Repositories\Contracts;

interface FileRepositoryInterface
{
    public function delete(int $id);

    public function update(array $data, int $id);

    public function store(array $data);
}
