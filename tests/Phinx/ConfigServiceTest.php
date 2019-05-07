<?php

use Peak\Database\Common\LaravelPhinxMigration;
use Peak\Database\Phinx\PhinxConfigService;
use Peak\Database\Phinx\PhinxEnvConfig;
use Peak\Database\Phinx\PhinxEnvConfigFactory;

class ConfigServiceTest extends \PHPUnit\Framework\TestCase
{

    protected $expected = [
        'paths' => [
            'migrations' => 'my/path',
        ],
        'migration_base_class' => LaravelPhinxMigration::class,
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
        $configService = new PhinxConfigService();

        $config = $configService->create(
            'my/path',
            LaravelPhinxMigration::class,
            'migrationsTable',
            'prod',
            [
                new PhinxEnvConfig('prod', [
                    'name' => 'name1',
                    'connection' => 'connection1',
                ]),
                new PhinxEnvConfig('dev', [
                    'name' => 'name2',
                    'connection' => 'connection2',
                ])
            ]
        );

        $this->assertTrue($config === $this->expected);
    }

    public function testEnvConfigFactory()
    {
        $configService = new PhinxConfigService();

        $config = $configService->create(
            'my/path',
            LaravelPhinxMigration::class,
            'migrationsTable',
            'prod',
            [
                new PhinxEnvConfigFactory('prod', function() {
                    return [
                        'name' => 'name1',
                        'connection' => 'connection1',
                    ];
                }),
                new PhinxEnvConfigFactory('dev', function() {
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