<?php

namespace Xylemical\Code\Processor;

use PHPUnit\Framework\TestCase;
use Xylemical\Code\DefinitionInterface;
use Xylemical\Code\Documentation;

class ProcessorTest extends TestCase {

  public function testProcessor() {
    $defA = new Documentation();
    $defB = new Documentation();

    $testA = new TestProcessor();
    $testB = new TestProcessor($defA);

    $processor = new Processor([$testB]);
    $this->assertTrue($processor->applies($defA));
    $this->assertFalse($processor->applies($defB));
    $this->assertTrue($processor->hasInstance(TestProcessor::class));
    $this->assertTrue($processor->hasProcessor($testB));

    $processor = new Processor([]);
    $this->assertFalse($processor->applies($defA));
    $this->assertFalse($processor->hasInstance(TestProcessor::class));
    $this->assertFalse($processor->hasProcessor($testA));

    $processor->addProcessor($testA);
    $this->assertEquals([$testA], $processor->getProcessors());
    $this->assertTrue($processor->hasInstance(TestProcessor::class));
    $this->assertFalse($processor->hasInstance(Processor::class));
    $this->assertTrue($processor->hasProcessor($testA));
    $this->assertFalse($processor->hasProcessor($testB));

    $processor->removeInstance(Processor::class);
    $this->assertEquals([$testA], $processor->getProcessors());
    $this->assertTrue($processor->hasInstance(TestProcessor::class));
    $this->assertTrue($processor->hasProcessor($testA));

    $processor->removeInstance(TestProcessor::class);
    $this->assertEquals([], $processor->getProcessors());
    $this->assertFalse($processor->hasInstance(TestProcessor::class));
    $this->assertFalse($processor->hasProcessor($testA));

    $processor->addProcessor($testA);
    $processor->removeProcessor($testB);
    $this->assertTrue($processor->hasInstance(TestProcessor::class));
    $this->assertTrue($processor->hasProcessor($testA));

    $processor->removeProcessor($testA);
    $this->assertFalse($processor->hasInstance(TestProcessor::class));
    $this->assertFalse($processor->hasProcessor($testA));

    $processor->addProcessors([$testA, $testB]);
    $this->assertEquals([$testA, $testB], $processor->getProcessors());

    $processor->process($defA);
    $processor->process($defB);

    $this->assertTrue(in_array($defA, $testA->processed, TRUE));
    $this->assertTrue(in_array($defB, $testA->processed, TRUE));
    $this->assertTrue(in_array($defA, $testB->processed, TRUE));
    $this->assertFalse(in_array($defB, $testB->processed, TRUE));
  }

}

class TestProcessor implements ProcessorInterface {

  public ?DefinitionInterface $target = NULL;

  public array $processed = [];

  public function __construct($target = NULL) {
    $this->target = $target;
  }

  public function applies(DefinitionInterface $definition): bool {
    return !$this->target || ($this->target === $definition);
  }

  public function process(DefinitionInterface $definition): static {
    $this->processed[] = $definition;
    return $this;
  }

}
