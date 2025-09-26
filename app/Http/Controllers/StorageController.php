<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

class StorageController extends Controller
{
    public function show(Request $request, $path)
    {
        Log::info('StorageController accessed', ['path' => $path, 'request_uri' => $request->getRequestUri()]);
        
        try {
            // Pastikan file ada di storage/app/public
            $filePath = 'public/' . $path;
            
            Log::info('Checking file', ['filePath' => $filePath, 'exists' => Storage::exists($filePath)]);
            
            if (!Storage::exists($filePath)) {
                Log::warning('File not found', ['filePath' => $filePath]);
                abort(404, 'File not found');
            }
            
            // Get file content dan mime type
            $file = Storage::get($filePath);
            $mimeType = Storage::mimeType($filePath);
            
            Log::info('File served successfully', ['filePath' => $filePath, 'mimeType' => $mimeType]);
            
            return Response::make($file, 200, [
                'Content-Type' => $mimeType,
                'Cache-Control' => 'public, max-age=31536000',
            ]);
            
        } catch (\Exception $e) {
            Log::error('StorageController error', ['error' => $e->getMessage(), 'path' => $path]);
            abort(404, 'File not found: ' . $e->getMessage());
        }
    }
}