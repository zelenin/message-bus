<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\MiddlewareBus\Middleware\HandlerMiddleware\Locator;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\IndexedReader;
use Doctrine\Common\Annotations\Reader;
use InvalidArgumentException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use ReflectionClass;
use RegexIterator;
use RuntimeException;
use Zelenin\MessageBus\MiddlewareBus\Middleware\HandlerMiddleware\Handler;
use Zelenin\MessageBus\MiddlewareBus\Middleware\HandlerMiddleware\Locator\Annotation\HandlerAnnotation;

final class AnnotationLocator implements Locator
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var string
     */
    private $path;

    /**
     * @var array
     */
    private $handlers;

    /**
     * @var callable
     */
    private $handlerResolver;

    /**
     * @param string $path
     * @param callable $handlerResolver
     */
    public function __construct(string $path, callable $handlerResolver)
    {
        $this->reader = new IndexedReader(new AnnotationReader());
        $this->path = $path;
        $this->handlerResolver = $handlerResolver;

        $this->registerLoader();

        $recursiveIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->path));
        $iterator = new RegexIterator($recursiveIterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

        foreach ($iterator as $filePath) {
            $content = file_get_contents($filePath[0]);
            $className = $this->getClassName($content);

            if ($className) {
                $reflClass = new ReflectionClass($className);
                $classAnnotations = $this->reader->getClassAnnotations($reflClass);

                if (isset($classAnnotations[HandlerAnnotation::class])) {
                    /** @var HandlerAnnotation $annotation */
                    $annotation = $classAnnotations[HandlerAnnotation::class];
                    if (isset($this->handlers[$annotation->message])) {
                        throw new InvalidArgumentException(sprintf('Handler for "%s" already exists.', $annotation->message));
                    }
                    $this->handlers[$annotation->message] = $className;
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function getHandler($message): Handler
    {
        $messageName = get_class($message);

        if (!isset($this->handlers[$messageName])) {
            throw new RuntimeException(sprintf('No handler for message "%s"', $messageName));
        }

        return call_user_func($this->handlerResolver, $this->handlers[$messageName]);
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function getClassName(string $content): string
    {
        $tokens = token_get_all($content);

        $namespaceCapturing = false;
        $classCapturing = false;

        $namespace = '';
        $className = '';

        foreach ($tokens as $token) {
            if ($token[0] == T_NAMESPACE) {
                $namespaceCapturing = true;
                continue;
            }

            if ($namespaceCapturing) {
                if (in_array($token[0], [T_STRING, T_NS_SEPARATOR], true)) {
                    $namespace .= $token[1];
                } else {
                    if ($namespace) {
                        $namespaceCapturing = false;
                    }
                }
            }

            if ($token[0] == T_CLASS) {
                $classCapturing = true;
                continue;
            }

            if ($classCapturing) {
                if ($token[0] === T_STRING) {
                    $className .= $token[1];
                } else {
                    if ($className || $token[0] !== T_WHITESPACE) {
                        break;
                    }
                }
            }
        }

        return $className ? sprintf("%s\%s", $namespace, $className) : '';
    }

    private function registerLoader()
    {
        if (file_exists(__DIR__ . '/../../../../../../../../vendor/autoload.php')) {
            $loader = require __DIR__ . '/../../../../../../../../vendor/autoload.php';
        } else {
            $loader = require __DIR__ . '/../../../../../vendor/autoload.php';
        }

        AnnotationRegistry::registerLoader([$loader, 'loadClass']);
    }
}
