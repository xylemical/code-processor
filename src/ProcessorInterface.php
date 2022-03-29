<?php

namespace Xylemical\Code\Processor;

use Xylemical\Code\DefinitionInterface;

/**
 * Provide processing on code definitions.
 */
interface ProcessorInterface {

  /**
   * Check the processor applies to the definition.
   *
   * @param \Xylemical\Code\DefinitionInterface $definition
   *   The definition.
   *
   * @return bool
   *   The result.
   */
  public function applies(DefinitionInterface $definition): bool;

  /**
   * Performs processing on the definition.
   *
   * @param \Xylemical\Code\DefinitionInterface $definition
   *   The definition.
   *
   * @return $this
   */
  public function process(DefinitionInterface $definition): static;

}
