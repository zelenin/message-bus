<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\Locator\Provider;

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
use Zelenin\MessageBus\Locator\Annotation\HandlerAnnotation;

final class AnnotationProvider implements Provider
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var Reader
     */
    private $reader;

    /**
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
        $this->reader = new IndexedReader(new AnnotationReader());

        $this->registerLoader();
    }

    /**
     * @return array
     */
    public function getHandlers(): array
    {
        $recursiveIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->path));
        $iterator = new RegexIterator($recursiveIterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

        $handlers = [];
        foreach ($iterator as $filePath) {
            $content = file_get_contents($filePath[0]);
            $className = $this->getClassName($content);

            if ($className) {
                $reflClass = new ReflectionClass($className);
                $classAnnotations = $this->reader->getClassAnnotations($reflClass);

                if (isset($classAnnotations[HandlerAnnotation::class])) {
                    /** @var HandlerAnnotation $annotation */
                    $annotation = $classAnnotations[HandlerAnnotation::class];
                    if (isset($handlers[$annotation->message])) {
                        throw new InvalidArgumentException(sprintf('Handler for "%s" already exists.', $annotation->message));
                    }
                    $handlers[$annotation->message] = $className;
                }
            }
        }

        return $handlers;
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
        if (file_exists(__DIR__ . '/../../../../../../vendor/autoload.php')) {
            $loader = require __DIR__ . '/../../../../../../vendor/autoload.php';
        } else {
            $loader = require __DIR__ . '/../../../vendor/autoload.php';
        }

        AnnotationRegistry::registerLoader([$loader, 'loadClass']);
    }
}
