<?php

namespace App\Livewire\Concerns;

use App\Exceptions\BusinessException;
use App\Helpers\ErrorLogger;
use Throwable;

trait HandlesErrors
{
    protected function runSafely(callable $callback, string $logMessage = 'Error tidak diketahui.', array $context = [])
    {
        try {
            return $callback();
        } catch (BusinessException $e) {
            // error bisnis yang akan dilihat user
            $this->addError('general', $e->getMessage());

            $this->dispatch(
                'swal',
                icon: 'error',
                text: $e->getMessage(),
                title: 'Gagal'
            );
        } catch (Throwable $e) {
            // error teknis, kasih pesan umum aja
            $errorId = ErrorLogger::log($e, $logMessage, $context);

            $this->dispatch(
                'swal',
                icon: 'error',
                text: 'Terjadi kesalahan sistem. Kode: '.$errorId, title: 'Gagal'
            );
        }
    }
}
