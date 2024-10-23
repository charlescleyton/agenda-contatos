<?php

namespace App\Repositories\Interfaces;

interface ContactRepositoryInterface
{
    public function getAll();
    public function store(array $data);
    public function search(string $query);
}
