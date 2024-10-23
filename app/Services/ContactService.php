<?php

namespace App\Services;

use App\Repositories\Interfaces\ContactRepositoryInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class ContactService
{
    protected $contactRepository;

    public function __construct(ContactRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function getAllContacts()
    {
        return $this->contactRepository->getAll();
    }

    public function storeContact(array $data)
    {
        $address = $this->getAddressFromCep($data['cep']);
        if (is_null($address)) {
            throw ValidationException::withMessages(['cep' => 'Invalid CEP. Please provide a valid one.']);
        }
        $data['address'] = $address;
        return $this->contactRepository->store($data);
    }

    public function searchContacts(string $query)
    {
        return $this->contactRepository->search($query);
    }

    private function getAddressFromCep($cep)
    {
        $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");

        if ($response->successful() && !$response->json('erro')) {
            return "{$response->json('logradouro')}, {$response->json('bairro')}, {$response->json('localidade')} - {$response->json('uf')}";
        }
        return null;
    }
}
