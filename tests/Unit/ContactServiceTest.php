<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class ContactServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     *
     * @return void
     */
    public function test_criacao_de_contato_com_cep_valido()
    {

        Http::fake([
            'viacep.com.br/*' => Http::response([
                'logradouro' => 'Rua Fictícia',
                'bairro' => 'Bairro Fictício',
                'localidade' => 'Cidade Fictícia',
                'uf' => 'SP',
                'erro' => false,
            ], 200),
        ]);

        $data = [
            'name' => 'João Silva',
            'phone' => '(11) 99999-9999',
            'email' => 'joao@example.com',
            'cep' => '01001-000',
        ];

        $response = $this->postJson('/api/contacts', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('contacts', [
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'phone' => '(11) 99999-9999',
            'cep' => '01001-000',
            'address' => 'Rua Fictícia, Bairro Fictício, Cidade Fictícia - SP',
        ]);
    }

    /**
     *
     * @return void
     */
    public function test_criacao_de_contato_com_cep_invalido()
    {
        Http::fake([
            'viacep.com.br/*' => Http::response([
                'erro' => true,
            ], 200),
        ]);

        $data = [
            'name' => 'Maria Santos',
            'phone' => '(21) 88888-8888',
            'email' => 'maria@example.com',
            'cep' => '99999-999',
        ];

        $response = $this->postJson('/api/contacts', $data);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors('cep');

        $response->assertJson([
            'errors' => [
                'cep' => ['Invalid CEP. Please provide a valid one.'],
            ]
        ]);
    }
}
