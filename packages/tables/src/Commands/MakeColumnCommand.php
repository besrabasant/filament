<?php

namespace Filament\Tables\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeColumnCommand extends Command
{
    use Concerns\CanManipulateFiles;

    protected $description = 'Creates a table column class and cell view.';

    protected $signature = 'make:table-column {name}';

    public function handle(): int
    {
        $column = (string) Str::of($this->argument('name'))
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->replace('/', '\\');
        $columnClass = (string) Str::of($column)->afterLast('\\');
        $columnNamespace = Str::of($column)->contains('\\') ?
            (string) Str::of($column)->beforeLast('\\') :
            '';

        $view = Str::of($column)
            ->prepend('tables\\columns\\')
            ->explode('\\')
            ->map(fn ($segment) => Str::kebab($segment))
            ->implode('.');

        $path = app_path(
            (string) Str::of($column)
                ->prepend('Tables\\Columns\\')
                ->replace('\\', '/')
                ->append('.php'),
        );
        $viewPath = resource_path(
            (string) Str::of($view)
                ->replace('.', '/')
                ->prepend('views/')
                ->append('.blade.php'),
        );

        if ($this->checkForCollision([
            $path,
        ])) {
            return static::INVALID;
        }

        $this->copyStubToApp('Column', $path, [
            'class' => $columnClass,
            'namespace' => 'App\\Tables\\Columns' . ($columnNamespace !== '' ? "\\{$columnNamespace}" : ''),
            'view' => $view,
        ]);

        if (! $this->fileExists($viewPath)) {
            $this->copyStubToApp('ColumnView', $viewPath);
        }

        $this->info("Successfully created {$column}!");

        return static::SUCCESS;
    }
}
