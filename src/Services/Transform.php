<?php

namespace EliPett\EntityTransformer\Services;

use EliPett\EntityTransformer\Contracts\EntityTransformer;
use Illuminate\Database\Eloquent\Model;

class Transform
{
    public static function entities($entities, EntityTransformer $transformer): array
    {
        $items = [];

        foreach ($entities as $entity) {
            $items[] = self::entity($entity, $transformer);
        }

        return [];
    }

    public static function entity(Model $entity, EntityTransformer $transformer): array
    {
        return [];
    }
}
