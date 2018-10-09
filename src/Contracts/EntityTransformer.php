<?php

namespace EliPett\EntityTransformer\Contracts;

interface EntityTransformer
{
    public function data($entity): array;
    public function relations(): array;
}
