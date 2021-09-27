<?php

require "vendor/autoload.php";

use PhpParser\Node;
use PhpParser\Error;
use PhpParser\Node\Name;
use PhpParser\NodeDumper;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;
use PhpParser\Node\Identifier;
use PhpParser\Node\Expr\Variable;
use PhpParser\NodeVisitorAbstract;


class IdentifierVisitor extends NodeVisitorAbstract
{

    /**
     * @var array
     */
    protected $variableMap;

    /**
     * @var int
     */
    protected $variableCount;

    /**
     * @var array
     */
    protected $identifierMap;

    /**
     * @var int
     */
    protected $identifierCount;

    public function beforeTraverse(array $nodes)
    {
        $this->variableMap = [];
        $this->identifierMap = [];
        $this->variableCount = 0;
        $this->identifierCount = 0;
    }

    public function enterNode(Node $node)
    {
        if ($node instanceof Identifier) {
            $this->addIdentifier($node->name);
        } elseif ($node instanceof Name) {
            foreach ($node->parts as $identifier) {
                $this->addIdentifier($identifier);
            }
        } elseif ($node instanceof Variable) {
            if (is_string($node->name) and !isset($this->variableMap[$node->name])) {
                while (true) {
                    $name = 'lI'[random_int(0, 1)];
                    for ($i = 0; $i < 9; ++$i) {
                        $name .= 'lI1'[random_int(0, 2)];
                    }
                    if (!in_array($name, $this->variableMap)) {
                        break;
                    }
                }
                $this->variableMap[$node->name] = $name;
                ++$this->variableCount;
            }
        }
    }

    /**
     * @return array
     */
    public function getVariableMap(): array
    {
        return $this->variableMap;
    }

    /**
     * @return array
     */
    public function getIdentifierMap(): array
    {
        return $this->identifierMap;
    }

    protected function addIdentifier($identifier)
    {
        if (!isset($this->identifierMap[$identifier])) {
            // bypass all built-ins
            if (function_exists($identifier) || class_exists($identifier)) {
                $this->identifierMap[$identifier] = $identifier;
                return;
            }

            $this->identifierMap[$identifier] = "i{$this->identifierCount}";
            ++$this->identifierCount;
        }
    }
}


class NormalizeVisitor extends NodeVisitorAbstract
{

    /**
     * @var array
     */
    protected $variableMap;

    /**
     * @var array
     */
    protected $identifierMap;

    public function beforeTraverse(array $nodes)
    {
        $traverser = new NodeTraverser();
        $visitor = new IdentifierVisitor();
        $traverser->addVisitor($visitor);
        $traverser->traverse($nodes);
        $this->variableMap = $visitor->getVariableMap();
        $this->identifierMap = $visitor->getIdentifierMap();
    }

    public function enterNode(Node $node)
    {
        if ($node instanceof Identifier) {
            $node->name = $this->identifierMap[$node->name];
        } elseif ($node instanceof Name) {
            foreach ($node->parts as $idx => $identifier) {
                $node->parts[$idx] = $this->identifierMap[$identifier];
            }
        } elseif (($node instanceof Variable) and (is_string($node->name))) {
            $node->name = $this->variableMap[$node->name];
            return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
        }
        return $node;
    }
}


if ($argc < 2) {
    die("Usage: {$argv[0]} filename\n");
}

$code = file_get_contents($argv[1]);

$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
try {
    $ast = $parser->parse($code);
} catch (Error $error) {
    die("Parse error: {$error->getMessage()}\n");
}

$traverser = new NodeTraverser();
$normalizeVisitor = new NormalizeVisitor();
$traverser->addVisitor($normalizeVisitor);
$ast = $traverser->traverse($ast);

// $prettyPrinter = new PrettyPrinter\Standard;
// echo $prettyPrinter->prettyPrintFile($ast);

$dumper = new NodeDumper;
echo $dumper->dump($ast) . "\n";
