<?php

use Enlightn\Enlightn\Composer;
use Illuminate\Container\Container;

class JsonReportBuilder
{
    /**
     * Get the project metadata for the JSON report.
     *
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function metadata()
    {
        return [
            'app_name' => config('app.name'),
            'app_env' => config('app.env'),
            'app_url' => config('app.url'),
            'project_name' => $this->getProjectName(),
        ];
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function getProjectName()
    {
        $composer = Container::getInstance()->make(Composer::class);

        try {
            $json = $composer->getJson();
        } catch (Throwable $throwable) {
            // Ignore any exceptions such as file not found.
            $json = [];
        }

        return $json['name'] ?? null;
    }
}
