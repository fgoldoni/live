<?php

namespace Core\Services;

use Core\Services\Contracts\ServiceInterface;
use Exception;
use Goldoni\CoreRepositories\Repositories\RepositoryAbstract;

/**
 * Class ServiceAbstract.
 */
abstract class ServiceAbstract implements ServiceInterface
{
    protected $repository;

    public function __construct()
    {
        $this->repository = $this->resolveRepository();
    }

    abstract protected function repository(): string;

    protected function resolveRepository()
    {
        $repository = app()->make($this->repository());

        if (! $repository instanceof RepositoryAbstract) {
            throw new Exception("Class {$this->repository()} must be an instance of App\\Repositories\\RepositoryAbstract");
        }

        return $repository;
    }
}
