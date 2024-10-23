<?php

namespace App\Repositories;

use App\Models\Contact;
use App\Repositories\Interfaces\ContactRepositoryInterface;

class ContactRepository implements ContactRepositoryInterface
{
    public function getAll()
    {
        return Contact::all();
    }

    public function store(array $data)
    {
        return Contact::create($data);
    }

    public function search(string $query)
    {
        return Contact::where('name', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%")
                      ->get();
    }
}
