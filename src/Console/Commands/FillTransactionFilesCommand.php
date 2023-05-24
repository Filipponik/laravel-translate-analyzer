<?php

declare(strict_types=1);

namespace Filipponik\LaravelTranslateAnalyzer\Console\Commands;

use Filipponik\TranslateAnalyzer\Analyzer;
use Illuminate\Console\Command;

class FillTransactionFilesCommand extends Command
{
    protected $signature = 'translate:fill-files';

    protected $description = 'Fill Laravel language files with exiting keys in your code';

    public function handle(): int
    {
        $languages = explode(' ', $this->ask('Select languages to fill (Example: en es ch)'));
        $versionToUse = $this->choice('Select Laravel version', ['8 and before', '9 and above']);

        $analyzer = new Analyzer();
        $analyzer
            ->setDirectoryPath(base_path())
            ->setSuffix('php')
            ->analyze('app');

        if ($versionToUse === '8 and before') {
            $analyzer->toLaravel8AndBefore($languages);

            $this->info('Files written successfully to '. base_path('/resources/lang/'). ' directory');
        } else {
            $analyzer->toLaravel9AndAbove($languages);
            $this->info('Files written successfully to '. base_path('/lang/'). ' directory');
        }

        return self::SUCCESS;
    }
}
