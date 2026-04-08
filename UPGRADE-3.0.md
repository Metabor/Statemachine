# Upgrading to metabor/statemachine 3.0

This document describes breaking changes introduced in version 3.0 and how to update your code.

## Requirements

- **PHP 8.2+** (previously PHP 5.3+)
- **PHPUnit 11** for development/testing (previously PHPUnit ^8.5)

---

## Breaking Changes

### 1. All interfaces now have native type declarations

Every method in `MetaborStd` now has full PHP native type declarations (parameter types and return types). If you implement these interfaces, you must update your method signatures accordingly.

#### `CallbackInterface`

```php
// Before
public function __invoke();

// After
public function __invoke(mixed ...$args): mixed;
```

#### `NamedInterface`

```php
// Before
public function getName();

// After
public function getName(): string;
```

#### `NamedCollectionInterface`

```php
// Before
public function add(NamedInterface $named);
public function has($name);
public function get($name);
public function getNames();

// After
public function add(NamedInterface $named): void;
public function has(string $name): bool;
public function get(string $name): NamedInterface;
public function getNames(): array;
```

#### `ArrayConvertableInterface`

```php
// Before
public function toArray();

// After
public function toArray(): array;
```

#### `MetadataInterface`

```php
// Before
public function getMetadata();

// After
public function getMetadata(): array;
```

#### `ConditionInterface`

```php
// Before
public function checkCondition($subject, \ArrayAccess $context);

// After
public function checkCondition(object $subject, \ArrayAccess $context): bool;
```

The `$subject` parameter is now typed as `object` (previously untyped).

#### `TransitionInterface`

```php
// Before
public function getTargetState();
public function isActive($subject, \ArrayAccess $context, $event = null);
public function getEventName();
public function getConditionName();

// After
public function getTargetState(): StateInterface;
public function isActive(object $subject, \ArrayAccess $context, ?EventInterface $event = null): bool;
public function getEventName(): ?string;
public function getConditionName(): ?string;
```

#### `StateInterface`

```php
// Before
public function getEventNames();
public function getEvent($name);
public function hasEvent($name);
public function getTransitions();

// After
public function getEventNames(): \Traversable|array;
public function getEvent(string $name): EventInterface;
public function hasEvent(string $name): bool;
public function getTransitions(): \Traversable;
```

#### `StateCollectionInterface`

```php
// Before
public function getStates();
public function getState($name);
public function hasState($name);

// After
public function getStates(): \Traversable;
public function getState(string $name): StateInterface;
public function hasState(string $name): bool;
```

#### `StatemachineInterface`

```php
// Before
public function getCurrentState();
public function triggerEvent($name, \ArrayAccess $context = null);
public function checkTransitions(\ArrayAccess $context = null);
public function getSubject();

// After
public function getCurrentState(): StateInterface;
public function triggerEvent(string $name, ?\ArrayAccess $context = null): void;
public function checkTransitions(?\ArrayAccess $context = null): void;
public function getSubject(): object;
```

#### `StatefulInterface`

```php
// Before
public function getCurrentStateName();
public function setCurrentStateName($stateName);

// After
public function getCurrentStateName(): string;
public function setCurrentStateName(string $stateName): void;
```

#### `StateNameDetectorInterface`

```php
// Before
public function detectCurrentStateName($subject);

// After
public function detectCurrentStateName(object $subject): ?string;
```

Note: Return type is now `?string` (nullable) to accommodate implementations that cannot determine the state name.

#### `DispatcherInterface`

```php
// Before
public function dispatch(EventInterface $event, array $arguments = array(), CallbackInterface $onReadyCallback = null);
public function isReady();

// After
public function dispatch(EventInterface $event, array $arguments = [], ?CallbackInterface $onReadyCallback = null): void;
public function isReady(): bool;
```

#### `ProcessInterface`

```php
// Before
public function getInitialState();

// After
public function getInitialState(): StateInterface;
```

#### `FactoryInterface`

```php
// Before
public function createStatemachine($subject);

// After
public function createStatemachine(object $subject): StatemachineInterface;
```

#### `ProcessDetectorInterface`

```php
// Before
public function detectProcess($subject);

// After
public function detectProcess(object $subject): ProcessInterface;
```

#### `WeightedInterface`

```php
// Before
public function getWeight(); // @return double

// After
public function getWeight(): float;
```

#### `TimeBasedInterface` / `LastStateHasChangedDateInterface`

```php
// Before
public function getDate(); // returned \DateTime

// After
public function getDate(): \DateTimeInterface;
public function getLastStateHasChangedDate(): \DateTimeInterface;
```

Return type changed from `\DateTime` to `\DateTimeInterface` to allow both `\DateTime` and `\DateTimeImmutable`.

---

### 2. `Callback::__invoke()` signature changed

```php
// Before
public function __invoke()
{
    $args = func_get_args();
    return call_user_func_array($this->callable, $args);
}

// After
public function __invoke(mixed ...$args): mixed
{
    return ($this->callable)(...$args);
}
```

### 3. `Composite::__invoke()` signature changed

```php
// Before
public function __invoke()
{
    $args = func_get_args();
    foreach ($this->callbacks as $callback) {
        call_user_func_array($callback, $args);
    }
}

// After
public function __invoke(mixed ...$args): mixed
{
    foreach ($this->callbacks as $callback) {
        $callback(...$args);
    }
    return null;
}
```

### 4. `Command::__invoke()` is no longer abstract — it throws by default

The base `Command` class now has a concrete `__invoke()` that throws a `\RuntimeException` if not overridden. Subclasses must implement `__invoke()` to provide actual behavior.

```php
// Before: abstract class, you had to declare __invoke abstract in subclass
abstract class MyCommand extends Command
{
    public function __invoke(): void
    {
        // your logic
    }
}

// After: same pattern still works, but the base class now has a default throwing implementation
abstract class MyCommand extends Command
{
    public function __invoke(mixed ...$args): void
    {
        // your logic
    }
}
```

---

## Migration Steps

1. **Update `composer.json`** to require PHP 8.2 and the new package version:
   ```json
   {
       "require": {
           "php": ">=8.2",
           "metabor/statemachine": "~3.0"
       }
   }
   ```

2. **Add return types** to all methods implementing `MetaborStd` interfaces. Your IDE or a static analysis tool like PHPStan will show you all affected locations.

3. **Update `$subject` type hints** from untyped or `mixed` to `object` in all classes implementing `ConditionInterface`, `FactoryInterface`, `ProcessDetectorInterface`, and `StateNameDetectorInterface`.

4. **Update `\DateTime` to `\DateTimeInterface`** in any code returning dates from `TimeBasedInterface` or `LastStateHasChangedDateInterface` implementations.

5. **Update custom `Command` subclasses** to use the variadic `mixed ...$args` signature for `__invoke()`.

6. **Run your test suite** to verify compatibility:
   ```bash
   composer update
   php vendor/bin/phpunit
   ```
