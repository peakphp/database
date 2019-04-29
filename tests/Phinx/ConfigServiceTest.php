<?php

use Peak\Database\Laravel\PhinxMigration;
use Peak\Database\Phinx\ConfigService;
use Peak\Database\Phinx\EnvConfig;
use Peak\Database\Phinx\EnvConfigFactory;

class ConfigServiceTest extends \PHPUnit\Framework\TestCase
{

    protected $expected = [
        'paths' => [
            'migrations' => 'my/path',
        ],
        'migration_base_class' => 'Peak\\Database\\Laravel\\PhinxMigration',
        'environments' => [
            'default_migration_table' => 'migrationsTable',
            'default_database' => 'prod',
            'prod' => [
                'name' => 'name1',
                'connection' => 'connection1',
            ],
            'dev' => [
                'name' => 'name2',
                'connection' => 'connection2',
            ],
        ],
    ];

    public function testConfig1()
    {
        $configService = new ConfigService();

        $config = $configService->create(
            'my/path',
            PhinxMigration::class,
            'migrationsTable',
            'prod',
            [
                new EnvConfig('prod', [
                    'name' => 'name1',
                    'connection' => 'connection1',
                ]),
                new EnvConfig('dev', [
                    'name' => 'name2',
                    'connection' => 'connection2',
                ])
            ]
        );

        $this->assertTrue($config === $this->expected);
    }

    public function testEnvConfigFactory()
    {
        $configService = new ConfigService();

        $config = $configService->create(
            'my/path',
            PhinxMigration::class,
            'migrationsTable',
            'prod',
            [
                new EnvConfigFactory('prod', function() {
                    return [
                        'name' => 'name1',
                        'connection' => 'connection1',
                    ];
                }),
                new EnvConfigFactory('dev', function() {
                    return [
                        'name' => 'name2',
                        'connection' => 'connection2',
                    ];
                }),
            ]
        );

        $this->assertTrue($config === $this->expected);
    }
}