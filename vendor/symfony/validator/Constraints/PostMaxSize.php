<?php

namespace Symfony\Component\Validator\Constraints;

use Symfony\Components\Validator\Constraint;

/**
 *  @Annotation
 */
class PostMaxSize extends Constraint {
    public $message = 'Maximum post data exceeded!';
}
