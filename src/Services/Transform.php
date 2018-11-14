<?php

namespace EliPett\EntityTransformer\Services;

use EliPett\EntityTransformer\Contracts\EntityTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Transform
{
    public static function entities($entities, string $transformer): array
    {
        $items = [];

        foreach ($entities as $entity) {
            $items[] = self::entity($entity, $transformer);
        }

        return $items;
    }

    public static function entity(Model $entity, string $entityTransformerPath): array
    {
        $data = [];

        $entityTransformer = self::getTransformerInstance($entityTransformerPath);

        foreach ($entityTransformer->data($entity) as $key => $value) {
            $data[$key] = $value;
        }

        foreach ($entityTransformer->relations() as $key => $relationTransformerPath) {
            if ($entity->relationLoaded($key)) {
                $data[$key] = self::relation($entity->$key, $relationTransformerPath);
            }

            $countKey = "{$key}_count";

            if ($entity->$countKey) {
                $data[$countKey] = $entity->$countKey;
            }
        }

        return $data;
    }

    private static function relation($relation, string $transformer): array
    {
        if ($relation instanceof Collection) {
            return self::entities($relation, $transformer);
        }

        if ($relation instanceof Model) {
            return self::entity($relation, $transformer);
        }

        throw new \InvalidArgumentException('Invalid Relation: ' . \get_class($relation));
    }

    private static function getTransformerInstance(string $class): EntityTransformer
    {
        $transformer = new $class();

        if (!$transformer instanceof EntityTransformer) {
            throw new \InvalidArgumentException("Invalid Transformer: $class");
        }

        return $transformer;
    }
}
