<?php

declare(strict_types=1);

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder as BaseBuilder;

class Builder extends BaseBuilder
{
    /**
     * Chunk the results of our query, but do not break when
     * we're doing an update while doing so (e.g. with where clauses).
     *
     * @param int $count
     * @param callable $callable
     * @return bool
     */
    public function chunk($count, callable $callable)
    {
        $page = 1;

        while (($chunk = $this->limit($count)->get())->count() > 0) {
            if ($callable($chunk, $page++) === false) {
                return false;
            }

            if ($chunk->count() < $count) {
                break;
            }
        }

        return true;
    }

    public function getRawSqlWithBindings(): string
    {
        $queryDump = str_replace(array('?'), array('\'%s\''), $this->toSql());

        return vsprintf($queryDump, $this->getBindings());
    }
}
