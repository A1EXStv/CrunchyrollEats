<?php

abstract class BaseModel implements ArrayAccess, JsonSerializable {
    

    // Allow property access via array syntax $obj['prop']
    public function offsetExists($offset): bool {
        $method = 'get' . ucfirst($offset);
        return method_exists($this, $method) || property_exists($this, $offset) || isset($this->$offset);
    }

    public function offsetGet($offset): mixed {
        $method = 'get' . ucfirst($offset);
        if (method_exists($this, $method)) {
            return $this->$method();
        }
        if (property_exists($this, $offset)) {
            return $this->$offset;
        }
        // Dynamic properties check
        if (isset($this->$offset)) {
            return $this->$offset;
        }
        return null;
    }

    public function offsetSet($offset, $value): void {
        $method = 'set' . ucfirst($offset);
        if (method_exists($this, $method)) {
            $this->$method($value);
        } else {
            // Allow setting dynamic properties (public access)
            $this->$offset = $value;
        }
    }

    public function offsetUnset($offset): void {
        unset($this->$offset);
    }
    
    // Magic methods for direct object access to dynamic properties
    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name ?? null;
    }

    // Default JSON serialization
    public function jsonSerialize(): array {
        return get_object_vars($this);
    }

    public function toArray(): array {
        return get_object_vars($this); 
    }
}
