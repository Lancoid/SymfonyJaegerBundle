# Lancoid SymfonyJaegerBundle

## Installation

### Choose tracer implementation

```bash
    composer req lancoid/symfony-jaeger-bundle
```

## Configuration

You can optionally configure environment variables, however, the default configuration will run fine out of the box for a tracing agent on localhost.
If you cannot change environment variables in your project, you can alternatively overwrite the container parameters directly.
```dotenv
JAEGER_OPENTRACING_HOSTNAME=project.host
JAEGER_OPENTRACING_AGENT_HOST=jaeger.host
JAEGER_OPENTRACING_PROJECT_NAME=project_name
```
## Usage

### Manual tracing

You can inject the tracing service automatically (via autowiring) or use the provided service alias `@lancoid_opentracing`.

#### Manual spanning

You can define spans manually, by using

```php
    Lancoid\SymfonyJaegerBundle\Service\Tracing::startActiveSpan(string $operationName, array $options = null): void
```

and 

```php
    Lancoid\SymfonyJaegerBundle\Service\Tracing::finishActiveSpan(): void
```

respectively.

`$operationName` is the displayed name of the trace operation,  
`$options` is an associative array of tracing options;  
the main usage is `$options['tags']`, which is an associative array of user defined tags (key value pairs).

#### Tagging spans

You can set tags (key value pairs) to the currently active span with

```php
    Lancoid\SymfonyJaegerBundle\Service\Tracing::setTagOfActiveSpan(string $key, string|bool|int|float $value): void
```

#### Logging in spans

You can always attach logs (key value pairs) to the currently active span with

```php
    Lancoid\SymfonyJaegerBundle\Service\Tracing::logInActiveSpan(array $fields): void
```

#### Baggage items

You can propagate baggage items (key value pairs) in-band across process boundaries with

```php
    Lancoid\SymfonyJaegerBundle\Service\Tracing::setBaggageItem(string $key, string $value): void
```

and retrieve them with 

```php
    Lancoid\SymfonyJaegerBundle\Service\Tracing::getBaggageItem(string $key): ?string
```

You should use this feature thoughtfully and with care. Every key and value is copied into every local and remote
child of the associated Span, and that can add up to a lot of network and cpu overhead.
