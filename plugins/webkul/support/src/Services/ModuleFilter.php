<?php

namespace Webkul\Support\Services;

class ModuleFilter
{
    protected static ?array $activeModules = null;

    /**
     * Get all active modules from config.
     */
    public static function getActive(): array
    {
        if (empty(static::$activeModules)) {
            // config/filament-shield.php loads alphabetically before config/modules.php,
            // so config('modules.*') can be unset when this runs during that file's parse.
            // Load modules.php directly in that case instead of caching an empty result.
            $modules = config('modules') ?? require config_path('modules.php');

            $configured = $modules['active'] ?? ['all'];

            if (in_array('all', $configured)) {
                static::$activeModules = array_keys($modules['mapping'] ?? []);
            } else {
                static::$activeModules = $configured;
            }
        }

        return static::$activeModules;
    }

    /**
     * Check if a specific module is active.
     */
    public static function isActive(string $module): bool
    {
        $active = static::getActive();

        return in_array('all', $active) || in_array($module, $active);
    }

    /**
     * Check if a navigation group should be visible.
     */
    public static function isGroupActive(string $group): bool
    {
        $active = static::getActive();

        if (in_array('all', $active)) {
            return true;
        }

        return in_array($group, $active);
    }

    /**
     * Get list of active plugin names (for service provider registration).
     */
    public static function getActivePlugins(): array
    {
        $active = static::getActive();
        $core = config('modules.core', []);
        $mapping = config('modules.mapping', []);

        if (in_array('all', $active)) {
            return array_merge($core, array_unique(
                array_reduce($mapping, fn ($carry, $plugins) => array_merge($carry, (array) $plugins), [])
            ));
        }

        $plugins = $core;

        foreach ($active as $module) {
            if (isset($mapping[$module])) {
                $plugins = array_merge($plugins, (array) $mapping[$module]);
            } else {
                $plugins[] = $module;
            }
        }

        return array_unique($plugins);
    }

    /**
     * Filter an array of items by module.
     *
     * @param  array<int, array{module?: string}>  $items
     * @return array<int, array{module?: string}>
     */
    public static function filterItems(array $items): array
    {
        $active = static::getActive();

        if (in_array('all', $active)) {
            return $items;
        }

        return array_filter($items, function ($item) use ($active) {
            $module = $item['module'] ?? null;

            if ($module === null) {
                return true;
            }

            return in_array($module, $active);
        });
    }

    /**
     * Get a summary of module status for debugging.
     *
     * @return array<string, array{active: bool, plugins: string[]}>
     */
    public static function getStatus(): array
    {
        $active = static::getActive();
        $mapping = config('modules.mapping', []);
        $status = [];

        foreach ($mapping as $group => $plugins) {
            $status[$group] = [
                'active'  => static::isGroupActive($group),
                'plugins' => (array) $plugins,
            ];
        }

        return $status;
    }
}
