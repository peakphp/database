<?php

namespace Peak\Database\Phinx;

class EnvConfig
{
    /**
     * @var string
     */
    private $envName;

    /**
     * @var array
     */
    private $envConfig;

    /**
     * @param string $envName
     * @param array $envConfig
     */
    public function __construct(string $envName, array $envConfig)
    {
        $this->envName = $envName;
        $this->envConfig = $envConfig;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            $this->envName => $this->envConfig
        ];
    }
}
