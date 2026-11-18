<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

Route::get('/consultar-dni/{dni}', function ($dni) {
    if (strlen($dni) !== 8 || !is_numeric($dni)) {
        return response()->json([
            'success' => false,
            'message' => 'El DNI debe tener exactamente 8 dígitos.'
        ], 400);
    }

    try {
        // Opción A: Si usas un servicio real de terceros (ej. apis.net.pe, decolectiva, etc.)
        // Reemplaza 'TU_TOKEN_AQUI' por tu token o déjalo libre si el servicio es público/gratuito.
        $response = Http::withToken('TU_TOKEN_AQUI')
            ->timeout(5)
            ->get("https://api.apis.net.pe/v1/dni?numero={$dni}");

        // Opción B: Si el servicio externo usa headers u otra estructura, ajústala aquí.
        if ($response->successful() && $response->json()) {
            $data = $response->json();
            
            // Adaptar las llaves según lo que devuelva la API que contrates/uses
            return response()->json([
                'success' => true,
                'dni' => $dni,
                'nombres' => $data['nombres'] ?? '',
                'apellidoPaterno' => $data['apellidoPaterno'] ?? $data['apellido_paterno'] ?? '',
                'apellidoMaterno' => $data['apellidoMaterno'] ?? $data['apellido_materno'] ?? '',
            ]);
        }

        // Si la API externa no encuentra el DNI
        return response()->json([
            'success' => false,
            'message' => 'No se encontraron datos para este DNI en el padrón.'
        ], 404);

    } catch (\Exception $e) {
        // En caso de fallo de red o que no haya internet localmente en tu PC de pruebas
        return response()->json([
            'success' => false,
            'message' => 'Error de conexión con el servicio de consulta DNI.'
        ], 500);
    }
});