<?php

namespace Treconyl\ActivityHistory;

use ReflectionClass;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;

use Treconyl\ActivityHistory\Observers\ModelObserver;

class ActivityHistoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish configuration file
        $this->publishes([
            __DIR__ . '/../config/activity-history.php' => config_path('activity-history.php'),
            // ...
        ], 'config');

        // Publish models file
        $this->publishes([
            __DIR__.'/Models/ActivityHistory.php' => app_path('Models/ActivityHistory.php'),
        ], 'models');

        // Publish migrations
        $this->publishes([
            __DIR__ . '/database/migrations' => database_path('migrations'),
        ], 'migrations');

        // Đăng ký các model để theo dõi
        $models = Cache::remember('models_list', 3600, function () {
            return $this->getAllModels();
        });

        foreach ($models as $model) {
            $model::observe(ModelObserver::class);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('ActivityHistory', ActivityHistoryServiceProvider::class);
    }

    /**
     * Get all Eloquent models from specified paths.
     *
     * @return array
     */
    protected function getAllModels()
    {
        $models = [];
        $paths = config('activity-history.paths'); // Lấy đường dẫn từ file cấu hình

        foreach ($paths as $path) {
            if (!is_dir($path)) {
                continue;
            }

            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
            foreach ($files as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $className = $this->getClassFromFile($file->getRealPath());
                    if ($className && class_exists($className)) {
                        $reflection = new ReflectionClass($className);
                        if ($reflection->isSubclassOf(\Illuminate\Database\Eloquent\Model::class) && !$reflection->isAbstract()) {
                            $models[] = $className;
                            // Log::info('Loaded modal: ' . $className);
                        }
                    }
                }
            }
        }

        return $models;
    }

    /**
     * Get class name from a file.
     *
     * @param string $file
     * @return string|null
     */
    protected function getClassFromFile($file)
    {
        $contents = file_get_contents($file);

        // Tìm namespace và class trong file
        if (preg_match('/namespace\s+([^\s;]+);/', $contents, $namespaceMatch) &&
            preg_match('/class\s+(\w+)/', $contents, $classMatch)) {
            
            return $namespaceMatch[1] . '\\' . $classMatch[1];
        }

        return null;
    }
}
