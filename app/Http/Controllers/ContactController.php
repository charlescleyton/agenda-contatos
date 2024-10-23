<?php

namespace App\Http\Controllers;

use App\Services\ContactService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ContactController extends Controller
{
    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    public function index()
    {
        return response()->json($this->contactService->getAllContacts());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'cep' => 'required|string',
        ]);

        try {
            $contact = $this->contactService->storeContact($request->all());
            return response()->json($contact, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function search(Request $request)
    {
        $query = $request->query('q');
        return response()->json($this->contactService->searchContacts($query));
    }
}


