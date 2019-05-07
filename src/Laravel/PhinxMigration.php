<?php

declare(strict_types=1);

namespace Peak\Database\Laravel;

use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Blueprint;
use Phinx\Migration\AbstractMigration;

class PhinxMigration extends AbstractMigration
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
        $this->db = ConnectionManager::getConnection($this->getEnvironment());
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
