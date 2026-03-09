<?php

namespace Lageg\Reporter\Contracts;

interface Chunkrizable
{
    /**@return \Lageg\Reporter\Contracts\Component[] */
    public function chunks(): array;
    public function filename(): string;
}
