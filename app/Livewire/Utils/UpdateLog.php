<?php

namespace App\Livewire\Utils;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class UpdateLog extends Component
{
    public ?bool $showLogUpdateModal = false;

    public function repositoryStats()
    {
        return Cache::remember('github_repo_stats_v2', 86400, function () {
            $token = config('services.github.token');
            $url = 'https://api.github.com/repos/razorext2/faceAttendanceV2/commits';

            // 1. Get total count from Link header
            $response = Http::withToken($token)->timeout(10)->get($url, ['per_page' => 1]);
            $link = $response->header('Link');
            $totalCommits = 0;

            if ($link && preg_match('/&page=(\d+)>; rel="last"/', $link, $matches)) {
                $totalCommits = (int) $matches[1];
            } else {
                $totalCommits = count($response->json());
            }

            // 2. Get first commit date
            $firstCommitDate = null;
            if ($totalCommits > 0) {
                $firstCommitResponse = Http::withToken($token)
                    ->timeout(10)
                    ->get($url, [
                        'per_page' => 1,
                        'page' => $totalCommits,
                    ]);
                $firstCommit = $firstCommitResponse->json()[0] ?? null;
                if ($firstCommit) {
                    $firstCommitDate = $firstCommit['commit']['committer']['date'] ?? null;
                }
            }

            return [
                'total_commits' => $totalCommits,
                'first_commit_date' => $firstCommitDate,
            ];
        });
    }

    public function logHistories()
    {
        if ($this->showLogUpdateModal !== true) {
            return collect();
        }

        return Cache::remember('github_commit_histories_v3', 1800, function () {
            $token = config('services.github.token');
            $repo = 'razorext2/faceAttendanceV2';

            $response = Http::withToken($token)
                ->timeout(10)
                ->get("https://api.github.com/repos/{$repo}/commits", [
                    'per_page' => 10,
                ]);

            if (! $response->successful()) {
                return collect();
            }

            $commits = $response->json();

            // Enrich with files data
            foreach ($commits as &$commit) {
                $sha = $commit['sha'];
                $commit['detailed_files'] = Cache::rememberForever("github_commit_files_{$sha}", function () use ($token, $repo, $sha) {
                    $detailResp = Http::withToken($token)
                        ->timeout(10)
                        ->get("https://api.github.com/repos/{$repo}/commits/{$sha}");

                    if (! $detailResp->successful()) {
                        return [];
                    }

                    $details = $detailResp->json();
                    $files = $details['files'] ?? [];

                    // Simplify file data to save cache space and bandwidth
                    return array_map(function ($file) {
                        return [
                            'name' => $file['filename'],
                            'status' => $file['status'], // added, modified, removed, renamed
                            'additions' => $file['additions'],
                            'deletions' => $file['deletions'],
                        ];
                    }, $files);
                });
            }

            return collect($commits);
        });
    }

    public function render()
    {
        return view('livewire.utils.update-log');
    }
}
