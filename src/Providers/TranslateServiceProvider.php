<?php

declare(strict_types=1);

namespace Filipponik\LaravelTranslateAnalyzer\Providers;

use Filipponik\LaravelTranslateAnalyzer\Console\Commands\CheckDuplicatesCommand;
use Filipponik\LaravelTranslateAnalyzer\Console\Commands\FillFilesCommand;
use Illuminate\Support\ServiceProvider;

class TranslateServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        $this->commands([
            CheckDuplicatesCommand::class,
            FillFilesCommand::class,
        ]);
    }
}
