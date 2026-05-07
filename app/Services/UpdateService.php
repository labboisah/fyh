<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Symfony\Component\Process\Process;

class UpdateService
{
    protected $repo = 'labboisah/fyh';
    protected $branch = 'main';

    public function getRemoteCommit()
    {
        $url = "https://api.github.com/repos/{$this->repo}/commits/{$this->branch}";

        $response = Http::withHeaders([
            'Accept' => 'application/vnd.github+json'
        ])->get($url);

        if ($response->successful()) {
            return $response->json()['sha'];
        }

        return null;
    }

    
    public function getLocalCommit()
    {
        $git = '"C:\\var\\bin\\git\\cmd\\git.exe"';

        $command = 'cd /d "'.base_path().'" && '.$git.' rev-parse HEAD 2>&1';

        return trim(shell_exec($command));

    }

    public function hasUpdate()
    {
        return $this->getRemoteCommit() !== $this->getLocalCommit();
    }
}