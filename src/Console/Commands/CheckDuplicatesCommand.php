<?php

declare(strict_types=1);

namespace Filipponik\LaravelTranslateAnalyzer\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use RecursiveDirectoryIterator;

class CheckDuplicatesCommand extends Command
{
    protected $signature = 'translate:check-duplicates';

    protected $description = 'Check value duplicates for language files';

    public function handle(): int
    {
        $filesArr = $this->parseFiles(base_path('resources/lang/en'), [], 'php');
        $duplicates = $this->getDuplicates($filesArr);
        $count = count($duplicates);
        if ($count === 0) {
            $this->info('Good job! No duplicates found!');
            return self::SUCCESS;
        }

        $this->warn("Found duplicates: $count");
        $this->newLine();
        $this->printDuplicates($duplicates);

        return self::SUCCESS;
    }

    protected function parseFiles(string $path, array $inputArr, string $suffix): array
    {
        $iterator = new RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS);

        do {
            $fileName = $iterator->getFilename();
            if ($fileName === '') {
                break;
            }

            if ($iterator->isReadable() && $iterator->isFile()) {
                $inputArr[$iterator->getBasename('.'.$suffix)] = require_once $iterator->getRealPath();
            } elseif ($iterator->isDir()) {
                $inputArr = $this->parseFiles($path . '/' . $iterator->getFilename(), $inputArr, $suffix);
            }
            $iterator->next();
        } while (true);

        return $inputArr;
    }

    protected function getDuplicates(array $inputArray): array
    {
        $inputArray = array_filter(Arr::dot($inputArray), fn ($element) => filled($element));
        $inputCollection = collect($inputArray);

        return collect(array_count_values($inputArray))
            ->reject(fn (int $count) => $count === 1)
            ->map(
                fn (string $value, string $key) => $inputCollection
                    ->filter(fn ($inputArrElement) => $inputArrElement === $key)
                    ->keys()
            )->toArray();
    }

    protected function printDuplicates(array $duplicates): void
    {
        $index = 0;
        foreach ($duplicates as $duplicate => $usages) {
            $index++;
            $this->warn("$index. \"$duplicate\" used at: ");
            $this->warn(implode(', ', $usages));
            $this->newLine();
        }
    }
}
