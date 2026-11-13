<?php

namespace App\Support;

use Illuminate\Foundation\PackageManifest as BasePackageManifest;

class PackageManifest extends BasePackageManifest
{
    /**
     * Build the manifest and write it to disk.
     *
     * Supports both Composer 1 (array) and Composer 2 ({"packages": [...]})
     * installed.json formats, which Laravel 5.8 does not handle by default.
     *
     * @return void
     */
    public function build()
    {
        $packages = [];

        if ($this->files->exists($path = $this->vendorPath.'/composer/installed.json')) {
            $installed = json_decode($this->files->get($path), true);

            $packages = isset($installed['packages']) ? $installed['packages'] : $installed;
        }

        $ignoreAll = in_array('*', $ignore = $this->packagesToIgnore());

        $this->write(collect($packages)->mapWithKeys(function ($package) {
            return [$this->format($package['name']) => $package['extra']['laravel'] ?? []];
        })->each(function ($configuration) use (&$ignore) {
            $ignore = array_merge($ignore, $configuration['dont-discover'] ?? []);
        })->reject(function ($configuration, $package) use ($ignore, $ignoreAll) {
            return $ignoreAll || in_array($package, $ignore);
        })->filter()->all());
    }
}
