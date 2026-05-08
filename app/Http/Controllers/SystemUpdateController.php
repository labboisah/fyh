<?php

namespace App\Http\Controllers;

use App\Services\UpdateService;

class SystemUpdateController extends Controller
{
    public function index(UpdateService $updateService)
    {
        // check internet connectivity
        if (!@fsockopen('github.com', 80)) {
            return back()->with('error', 'Unable to connect to GitHub. Please check your internet connection.');
        }
        
        return view('admin.update', [
            'hasUpdate' => $updateService->hasUpdate(),
            'local' => $updateService->getLocalCommit(),
            'remote' => $updateService->getRemoteCommit(),
        ]);
    }

    public function update()
    {
        $git = '"C:\\var\\bin\\git\\cmd\\git.exe"';

        $basePath = base_path();

        $steps = [

            [
                'title' => 'Pulling latest update...',
                'command' => 'cd /d "'.$basePath.'" && '.$git.' pull origin main 2>&1',
            ],

            [
                'title' => 'Installing composer packages...',
                'command' => 'cd /d "'.$basePath.'" && composer install 2>&1',
            ],

            [
                'title' => 'Running database migrations...',
                'command' => 'cd /d "'.$basePath.'" && php artisan migrate --force 2>&1',
            ],

            [
                'title' => 'Clearing optimization cache...',
                'command' => 'cd /d "'.$basePath.'" && php artisan optimize:clear 2>&1',
            ],
        ];

        $results = [];

        foreach ($steps as $index => $step) {

            $output = [];
            $returnCode = null;

            exec($step['command'], $output, $returnCode);

            $results[] = [
                'step' => $index + 1,
                'title' => $step['title'],
                'output' => $output,
                'return_code' => $returnCode,
                'progress' => intval((($index + 1) / count($steps)) * 100),
            ];

            if ($returnCode !== 0) {

                return response()->json([
                    'success' => false,
                    'results' => $results,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'results' => $results,
        ]);
    }
}