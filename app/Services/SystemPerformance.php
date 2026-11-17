<?php

namespace App\Services;

use Symfony\Component\Process\Exception\ExceptionInterface;
use Symfony\Component\Process\Process;

class SystemPerformance
{
    public function read(): array
    {
        $windows = $this->readWindowsMetrics();
        $projectPath = base_path();

        return [
            'cpu_percent' => is_numeric($windows['cpu'] ?? null)
                ? round((float) $windows['cpu'], 1)
                : null,
            'memory' => $this->usage(
                (float) ($windows['memory_total_kib'] ?? 0) * 1024,
                isset($windows['memory_free_kib'])
                    ? (float) $windows['memory_free_kib'] * 1024
                    : null,
            ),
            'storage' => $this->usage(
                disk_total_space($projectPath),
                disk_free_space($projectPath),
            ),
        ];
    }

    private function readWindowsMetrics(): array
    {
        if (PHP_OS_FAMILY !== 'Windows') {
            return [];
        }

        if (! function_exists('proc_open')) {
            logger()->warning('TinyGuide performance metrics: proc_open unavailable', ['sapi' => PHP_SAPI]);

            return [];
        }

        $windowsDirectory = getenv('SystemRoot') ?: getenv('windir') ?: 'C:\Windows';
        $powerShellPath = rtrim($windowsDirectory, '\\/') . '\System32\WindowsPowerShell\v1.0\powershell.exe';
        $useSystemPath = is_file($powerShellPath);

        $script = <<<'POWERSHELL'
$cpu = Get-CimInstance Win32_Processor -ErrorAction Stop | Measure-Object -Property LoadPercentage -Average;
$memory = Get-CimInstance Win32_OperatingSystem -ErrorAction Stop;
@{ cpu = $cpu.Average; memory_total_kib = $memory.TotalVisibleMemorySize; memory_free_kib = $memory.FreePhysicalMemory } | ConvertTo-Json -Compress
POWERSHELL;

        try {
            $process = new Process([
                $useSystemPath ? $powerShellPath : 'powershell.exe',
                '-NoProfile',
                '-NonInteractive',
                '-Command',
                str_replace(["\r", "\n"], ' ', $script),
            ], null, $useSystemPath ? ['SystemRoot' => $windowsDirectory, 'windir' => $windowsDirectory] : null);

            $process->setTimeout(6);
            $process->run();
        } catch (ExceptionInterface $exception) {
            logger()->warning('TinyGuide performance metrics: PowerShell did not complete', [
                'sapi' => PHP_SAPI,
                'exception' => get_class($exception),
            ]);

            return [];
        }

        if (! $process->isSuccessful()) {
            logger()->warning('TinyGuide performance metrics: PowerShell returned an error', [
                'sapi' => PHP_SAPI,
                'exit_code' => $process->getExitCode(),
                'error' => substr(trim($process->getErrorOutput()), 0, 300),
            ]);

            return [];
        }

        $metrics = json_decode($process->getOutput(), true);

        if (! is_array($metrics) || ! isset($metrics['cpu'], $metrics['memory_total_kib'], $metrics['memory_free_kib'])) {
            logger()->warning('TinyGuide performance metrics: incomplete Windows readings', [
                'sapi' => PHP_SAPI,
                'json_error' => json_last_error_msg(),
            ]);
        }

        return is_array($metrics) ? $metrics : [];
    }

    private function usage(float|false $total, float|false|null $free): ?array
    {
        if ($total <= 0 || $free === false || $free === null || $free < 0 || $free > $total) {
            return null;
        }

        $used = $total - $free;

        return [
            'used_bytes' => $used,
            'total_bytes' => $total,
            'percent' => round($used / $total * 100, 1),
        ];
    }
}