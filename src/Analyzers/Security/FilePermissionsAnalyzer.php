<?php

namespace Enlightn\Enlightn\Analyzers\Security;

use Illuminate\Support\Str;

class FilePermissionsAnalyzer extends SecurityAnalyzer
{
    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'Your project files and directories use safe permissions.';

    /**
     * The severity of the analyzer.
     *
     * @var string|null
     */
    public $severity = self::SEVERITY_CRITICAL;

    /**
     * The time to fix in minutes.
     *
     * @var int|null
     */
    public $timeToFix = 60;

    /**
     * @var string
     */
    protected $unsafeFilesOrDirs;

    /**
     * Get the error message describing the analyzer insights.
     *
     * @return string
     */
    public function errorMessage()
    {
        return "Your application's project directory permissions are not setup in a secure manner. This may "
            ."expose your application to be compromised if another account on the same server is vulnerable. "
            ."This can be even more dangerous if you used shared hosting. All project directories in Laravel "
            ."should be setup with a max of 755 permissions and most app files should be provided 644 (except "
            ."executables such as Artisan or your deployment scripts which should be provided 755 permissions). "
            ."These are the max level of permissions in order to be secure. Your unsafe files or directories "
            ."include: {$this->unsafeFilesOrDirs}.";
    }

    /**
     * Execute the analyzer.
     *
     * @return void
     */
    public function handle()
    {
        $filesOrDirectoriesToCheck = config('enlightn.allowed_permissions', [
            base_path() => '755',
            app_path() => '755',
            resource_path() => '755',
            storage_path() => '755',
            public_path() => '755',
            config_path() => '755',
            database_path() => '755',
            base_path('routes') => '755',
            app()->bootstrapPath() => '755',
            app()->bootstrapPath('cache') => '755',
            app()->bootstrapPath('app.php') => '644',
            base_path('artisan') => '755',
            public_path('index.php') => '644',
            public_path('server.php') => '644',
        ]);

        $this->unsafeFilesOrDirs = collect($filesOrDirectoriesToCheck)->filter(function ($allowedPermission, $path) {
           return file_exists($path) && ($allowedPermission < decoct(fileperms($path) & 0777));
        })->keys()->map(function ($path) {
            return Str::contains($path, base_path())
                ? ('['.Str::after($path, base_path()).']') : '['.$path.']';
        })->join(', ', ' and ');

        if (! empty($this->unsafeFilesOrDirs)) {
            $this->markFailed();
        }
    }
}
