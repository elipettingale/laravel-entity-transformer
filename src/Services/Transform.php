<?php

namespace EliPett\EntityTransformer\Services;

use EliPett\EntityTransformer\Contracts\EntityTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Transform
{
    public function entities($entities, string $transformer): array
    {
        $items = [];

        foreach ($entities as $entity) {
            $items[] = $this->entity($entity, $transformer);
        }

        return $items;
    }

    public function entity(Model $entity, string $entityTransformerPath): array
    {
        $data = [];

        $entityTransformer = $this->getTransformerInstance($entityTransformerPath);

        foreach ($entityTransformer->data($entity) as $key => $value) {
            $data[$key] = $value;
        }

        foreach ($entityTransformer->relations() as $key => $relationTransformerPath) {
            if ($entity->relationLoaded($key)) {
                $data[$key] = $this->relation($entity->$key, $relationTransformerPath);
            }

            $countKey = "{$key}_count";

            if ($entity->$countKey) {
                $data[$countKey] = $entity->$countKey;
            }
        }

        return $data;
    }

    private function relation($relation, string $transformer): array
    {
        if ($relation instanceof Collection) {
            return $this->entities($relation, $transformer);
        }

        if ($relation instanceof Model) {
            return $this->entity($relation, $transformer);
        }

        throw new \InvalidArgumentException('Invalid Relation: ' . \get_class($relation));
    }

    private function getTransformerInstance(string $class): EntityTransformer
    {
        $transformer = new $class();

        if (!$transformer instanceof EntityTransformer) {
            throw new \InvalidArgumentException("Invalid Transformer: $class");
        }

        return $transformer;
    }
}
