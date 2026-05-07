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
        try {

            $git = '"C:\\var\\bin\\git\\cmd\\git.exe"';

            $basePath = base_path();

            $commands = [

                'cd /d "'.$basePath.'" && '.$git.' pull origin main 2>&1',

                'cd /d "'.$basePath.'" && composer install 2>&1',

                'cd /d "'.$basePath.'" && php artisan migrate --force 2>&1',

                'cd /d "'.$basePath.'" && php artisan optimize:clear 2>&1',
            ];

            $logs = [];

            foreach ($commands as $command) {

                $output = [];
                $returnCode = null;

                exec($command, $output, $returnCode);

                $logs[] = [
                    'command' => $command,
                    'output' => $output,
                    'return_code' => $returnCode,
                ];
            }

            return back()->with('success', 'System updated successfully')
                        ->with('logs', $logs);

        } catch (\Throwable $e) {

            return back()->with('error', $e->getMessage());
        }
    }
}