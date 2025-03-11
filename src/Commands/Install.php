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
        $this->createLayout('compare');
        $page = $this->createPage('Compare', 'compare', 'compare');
        setting([
            'pages.compare' => $page,
        ]);
        $this->call('optimize:clear');
        $this->info('Installed Smart CMS compare module');
    }

    public function createLayout(string $name)
    {
        $this->call('make:layout', [
            'name' => $name,
        ]);
        Layout::query()->updateOrCreate(
            [
                'path' => $name . '/' . $name,
            ],
            [
                'name' => ucfirst($name),
                'template' => template(),
                'status' => 1,
                'schema' => [],
                'value' => [],
            ]
        );
    }

    public function createPage(string $name, string $slug, string $layout): int
    {
        $page = Page::query()->updateOrCreate(
            ['slug' => $slug],
            [
                'name' => $name,
                'layout' => Layout::query()->where('path', $layout . '/' . $layout)->first()->id,
            ]
        );

        return $page->id;
    }
}
