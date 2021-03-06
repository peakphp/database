<?php

namespace Peak\Database\Phinx;

use \Closure;

class PhinxEnvConfigFactory implements PhinxEnvConfigInterface
{
    /**
     * @var string
     */
    private $envName;

    /**
     * @var Closure
     */
    private $envConfigClosure;

    /**
     * PhinxEnvConfigFactory constructor.
     * @param string $envName
     * @param Closure $envConfigClosure
     */
    public function __construct(string $envName, Closure $envConfigClosure)
    {
        $this->envName = $envName;
        $this->envConfigClosure = $envConfigClosure;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        $fn = $this->envConfigClosure;
        return [
            $this->envName => $fn()
        ];
    }
}
