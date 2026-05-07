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

            $output = [];

            exec('git pull origin main 2>&1', $output);

            exec('composer install --no-interaction 2>&1');

            exec('php artisan migrate --force 2>&1');

            exec('php artisan optimize:clear 2>&1');

            return back()->with('success', 'System updated successfully');

        } catch (\Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }
}