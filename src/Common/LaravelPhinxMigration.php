<?php

declare(strict_types=1);

namespace Peak\Database\Common;

use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Blueprint;
use Peak\Database\Laravel\LaravelConnectionManager;
use Phinx\Migration\AbstractMigration;

class LaravelPhinxMigration extends AbstractMigration
{
    /**
     * @var Connection
     */
    protected $db;

    /**
     * Prepare migration script
     * @throws \Exception
     */
    public function init()
    {
        $this->db = LaravelConnectionManager::getConnection($this->getEnvironment());
    }

    /**
     * @param Blueprint $table
     */
    protected function tsColumns(Blueprint &$table)
    {
        $table->timestamp('createdAt')->useCurrent();
        $table->timestamp('updatedAt')->default($this->db->raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        $table->timestamp('deletedAt')->nullable(true)->default(null);
    }
}
