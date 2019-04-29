<?php

declare(strict_types=1);

namespace Peak\Database\Phinx;

class ConfigService
{
    /**
     * @param mixed $paths
     * @param string $migrationBaseClass
     * @param string $envDefaultMigrationTable
     * @param string $envDefaultDatabase
     * @param array<EnvConfig> $envs
     * @return array
     */
    public function create(
        $paths,
        string $migrationBaseClass,
        string $envDefaultMigrationTable,
        string $envDefaultDatabase,
        array $envs
    ): array {

        $config = [
            'paths' => [
                'migrations' => $paths
            ],
            'migration_base_class' => $migrationBaseClass,
            'environments' => [
                'default_migration_table' => $envDefaultMigrationTable,
                'default_database' => $envDefaultDatabase,
            ]
        ];

        foreach ($envs as $env) {
            $config['environments'] = array_merge($config['environments'], $this->envConfig($env));
        }

        return $config;
    }

    /**
     * @param EnvConfigInterface $env
     * @return array
     */
    private function envConfig(EnvConfigInterface $env): array
    {
        return $env->getConfig();
    }
}
