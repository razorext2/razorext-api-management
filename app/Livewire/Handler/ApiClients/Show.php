<?php

declare(strict_types=1);

namespace App\Livewire\Handler\ApiClients;

use App\Livewire\Concerns\HandlesErrors;
use App\Models\ApiClient;
use App\Models\ApiRequestLog;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

final class Show extends Component
{
    use HandlesErrors, WithPagination;

    public ApiClient $client;

    #[Url(as: 'q', history: true)]
    public string $search = '';

    #[Url(as: 'method', history: true)]
    public string $method = '';

    #[Url(as: 'status', history: true)]
    public string $status = '';

    #[Url(as: 'period', history: true)]
    public string $period = 'all';

    public int $perPage = 15;

    public bool $autoRefresh = false;

    public ?int $selectedLogId = null;

    public function mount(ApiClient $client): void
    {
        $this->client = $client;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingMethod(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingPeriod(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'method', 'status', 'period']);
        $this->perPage = 15;
        $this->resetPage();
    }

    public function inspectLog(int $logId): void
    {
        $this->selectedLogId = $logId;
    }

    public function closeInspectModal(): void
    {
        $this->selectedLogId = null;
    }

    public function toggleAutoRefresh(): void
    {
        $this->autoRefresh = ! $this->autoRefresh;
    }

    public function clearLogs(): void
    {
        if (! Auth::user()?->can('api-clients-delete')) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Akses Ditolak',
                'text' => 'Anda tidak memiliki izin untuk menghapus riwayat request log.',
            ]);

            return;
        }

        $this->runSafely(function () {
            $deletedCount = ApiRequestLog::where('api_client_id', $this->client->id)->delete();

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Riwayat Dibersihkan',
                'text' => "Sebanyak {$deletedCount} catatan riwayat request berhasil dihapus.",
            ]);

            $this->resetPage();
        }, 'Gagal membersihkan riwayat request log', [
            'client_id' => $this->client->id,
            'user_id' => Auth::id(),
        ]);
    }

    public function render(): View
    {
        // 1. Calculate Summary Metrics for this client
        $statsQuery = ApiRequestLog::where('api_client_id', $this->client->id);

        $totalRequests = (clone $statsQuery)->count();
        $successCount = (clone $statsQuery)->whereBetween('status_code', [200, 299])->count();
        $error4xxCount = (clone $statsQuery)->whereBetween('status_code', [400, 499])->count();
        $error5xxCount = (clone $statsQuery)->whereBetween('status_code', [500, 599])->count();
        $avgExecutionTime = (clone $statsQuery)->avg('execution_time_ms') ?? 0.0;

        $successRate = $totalRequests > 0
            ? round(($successCount / $totalRequests) * 100, 1)
            : 100.0;

        // 2. Build Filtered Request Logs Query
        $logsQuery = ApiRequestLog::where('api_client_id', $this->client->id)
            ->when(! empty($this->search), function (Builder $query): void {
                $term = '%'.trim($this->search).'%';
                $query->where(function (Builder $q) use ($term): void {
                    $q->where('endpoint', 'like', $term)
                        ->orWhere('ip_address', 'like', $term)
                        ->orWhere('user_agent', 'like', $term)
                        ->orWhere('error_message', 'like', $term);
                });
            })
            ->when(! empty($this->method), function (Builder $query): void {
                $query->where('method', strtoupper($this->method));
            })
            ->when(! empty($this->status), function (Builder $query): void {
                if ($this->status === '2xx') {
                    $query->whereBetween('status_code', [200, 299]);
                } elseif ($this->status === '4xx') {
                    $query->whereBetween('status_code', [400, 499]);
                } elseif ($this->status === '5xx') {
                    $query->whereBetween('status_code', [500, 599]);
                } else {
                    $query->where('status_code', (int) $this->status);
                }
            })
            ->when($this->period !== 'all', function (Builder $query): void {
                if ($this->period === 'today') {
                    $query->whereDate('created_at', Carbon::today());
                } elseif ($this->period === '7d') {
                    $query->where('created_at', '>=', Carbon::now()->subDays(7));
                } elseif ($this->period === '30d') {
                    $query->where('created_at', '>=', Carbon::now()->subDays(30));
                }
            })
            ->orderByDesc('id');

        $logs = $logsQuery->paginate($this->perPage);

        // 3. Selected Log Detail for Modal (if any)
        $selectedLog = $this->selectedLogId
            ? ApiRequestLog::where('api_client_id', $this->client->id)->find($this->selectedLogId)
            : null;

        return view('livewire.handler.api-clients.show', [
            'totalRequests' => $totalRequests,
            'successCount' => $successCount,
            'error4xxCount' => $error4xxCount,
            'error5xxCount' => $error5xxCount,
            'avgExecutionTime' => round((float) $avgExecutionTime, 2),
            'successRate' => $successRate,
            'logs' => $logs,
            'selectedLog' => $selectedLog,
        ]);
    }
}
