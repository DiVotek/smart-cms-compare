<?php

namespace SmartCms\Compare\Commands;

use Illuminate\Console\Command;
use SmartCms\Core\Models\Layout;
use SmartCms\Core\Models\Page;

class Install extends Command
{
    protected $signature = 'compare:install';

    protected $description = 'Install Smart CMS compare module';

    public function handle()
    {
        $this->call('make:layout', [
            'name' => 'compare.default',
        ]);
        $layout = Layout::query()->where('path', 'compare.default')->first();
        if (!$layout) {
            $this->error('Layout compare.default not found');
            return;
        }
        $page = Page::query()->updateOrCreate(
            ['slug' => 'compare'],
            [
                'name' => 'Compare',
                'layout_id' => $layout->id,
            ]
        );
        setting([
            'pages.compare' => $page->id,
        ]);
        $this->call('optimize:clear');
        $this->info('Installed Smart CMS compare module');
    }
}
