<?php

namespace Xylemical\Code\Processor;

use Xylemical\Code\DefinitionInterface;

/**
 * Provides processing using multiple processors.
 */
class Processor implements ProcessorInterface {

  /**
   * The processors.
   *
   * @var \Xylemical\Code\Processor\ProcessorInterface[]
   */
  protected array $processors = [];

  /**
   * Processor constructor.
   *
   * @param array $processors
   *   The processors.
   */
  public function __construct(array $processors) {
    foreach ($processors as $processor) {
      $this->addProcessor($processor);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function applies(DefinitionInterface $definition): bool {
    foreach ($this->processors as $processor) {
      if ($processor->applies($definition)) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function process(DefinitionInterface $definition): static {
    foreach ($this->processors as $processor) {
      if ($processor->applies($definition)) {
        $processor->process($definition);
      }
    }
    return $this;
  }

  /**
   * Get the processors.
   *
   * @return \Xylemical\Code\Processor\ProcessorInterface[]
   *   The processors.
   */
  public function getProcessors(): array {
    return $this->processors;
  }

  /**
   * Check there is a processor in the processor list.
   *
   * @param \Xylemical\Code\Processor\ProcessorInterface $processor
   *   The processor.
   *
   * @return bool
   *   The result.
   */
  public function hasProcessor(ProcessorInterface $processor): bool {
    foreach ($this->processors as $item) {
      if ($item === $processor) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * Check there is an instance of a processor in the processor list.
   *
   * @param string $instanceof
   *   The instanceof class name.
   *
   * @return bool
   *   The result.
   */
  public function hasInstance(string $instanceof): bool {
    foreach ($this->processors as $item) {
      if ($item instanceof $instanceof) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * Add a processor to the processors.
   *
   * @param \Xylemical\Code\Processor\ProcessorInterface $processor
   *   The processor.
   *
   * @return $this
   */
  public function addProcessor(ProcessorInterface $processor): static {
    if (!in_array($processor, $this->processors)) {
      $this->processors[] = $processor;
    }
    return $this;
  }

  /**
   * Add multiple processors to the list.
   *
   * @param \Xylemical\Code\Processor\Processor[] $processors
   *   The processors.
   *
   * @return $this
   */
  public function addProcessors(array $processors): static {
    foreach ($processors as $processor) {
      $this->addProcessor($processor);
    }
    return $this;
  }

  /**
   * Remove the processor.
   *
   * @param \Xylemical\Code\Processor\ProcessorInterface $processor
   *   The processor.
   *
   * @return $this
   */
  public function removeProcessor(ProcessorInterface $processor): static {
    $this->processors = array_filter($this->processors, function ($item) use ($processor) {
      return $item !== $processor;
    });
    return $this;
  }

  /**
   * Remove processors by an instanceof check.
   *
   * @param string $instanceof
   *   The instanceof class/interface.
   *
   * @return $this
   */
  public function removeInstance(string $instanceof): static {
    $this->processors = array_filter($this->processors, function ($item) use ($instanceof) {
      return !($item instanceof $instanceof);
    });
    return $this;
  }

}
