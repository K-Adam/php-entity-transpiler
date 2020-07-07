<?php

namespace EntityTranspiler;

class EntityCollection {

  private $entities = [];

  public function add(Entity $entity) {
      $this->entities[$entity->getClassRef()->getFullName()] = $entity;
  }

  public function hasName(string $fullName): bool {
      return array_key_exists($fullName, $this->entities);
  }

  public function getByName(string $fullName) :? Entity {
      return $this->entities[$fullName] ?? null;
  }

  /** @return Entity[] **/
  public function getEntities(): array {
      return array_values($this->entities);
  }


}
