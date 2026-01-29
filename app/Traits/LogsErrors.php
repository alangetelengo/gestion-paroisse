<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Throwable;

trait LogsErrors
{
    /**
     * Log une erreur dans le canal paroisse (storage/logs/paroisse.log).
     * Accepte : logError($exception, 'message', $context) ou logError('message', $exception, $context).
     */
    protected function logError(Throwable|string $first, Throwable|string|null $second = null, array $context = []): void
    {
        $message = is_string($first) ? $first : (is_string($second) ? $second : 'Erreur');
        $exception = $first instanceof Throwable ? $first : ($second instanceof Throwable ? $second : null);

        $logContext = array_merge([
            'user_id' => auth()->id(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'ip' => request()->ip(),
        ], $context);

        if ($exception) {
            $logContext['exception'] = [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ];
        }

        Log::channel('paroisse')->error($message, $logContext);
    }

    /**
     * Log une information dans le canal paroisse
     */
    protected function logInfo(string $message, array $context = []): void
    {
        $logContext = array_merge([
            'user_id' => auth()->id(),
            'url' => request()->fullUrl(),
        ], $context);

        Log::channel('paroisse')->info($message, $logContext);
    }

    /**
     * Log un avertissement dans le canal paroisse
     */
    protected function logWarning(string $message, array $context = []): void
    {
        $logContext = array_merge([
            'user_id' => auth()->id(),
            'url' => request()->fullUrl(),
        ], $context);

        Log::channel('paroisse')->warning($message, $logContext);
    }
}
