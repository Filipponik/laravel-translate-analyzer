<?php

declare(strict_types=1);

namespace Filipponik\LaravelTranslateAnalyzer\Console\Commands;

use Filipponik\TranslateAnalyzer\Analyzer;
use Illuminate\Console\Command;

class FillFilesCommand extends Command
{
    protected $signature = 'translate:fill-files';

    protected $description = 'Fill Laravel language files with exiting keys in your code';

    public function handle(): int
    {
        $languages = explode(' ', $this->ask('Select languages to fill with space as separator (Example: en es ch)'));
        $versionToUse = $this->choice('Select Laravel version', ['8 and before', '9 and above']);

        $analyzer = new Analyzer();
        $analyzer
            ->directory(base_path())
            ->suffix('php')
            ->analyze('app');

        if ($versionToUse === '8 and before') {
            $analyzer->toLaravel8AndBefore($languages);
            $dir = base_path('/resources/lang/');
        } else {
            $analyzer->toLaravel9AndAbove($languages);
            $dir = base_path('/lang/');
        }
        $this->info("Files written successfully to $dir");

        return self::SUCCESS;
    }
}
