<?php
/**
 * Copyright 2011 Zikula Foundation.
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package Zikula
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

namespace UsersModule\Controller\FormData\Validator;

use Zikula\Component\DependencyInjection\ContainerBuilder;

/**
 * Validates a field's value, ensuring that its string value matches a PCRE regular expression.
 */
class StringRegularExpression extends AbstractValidator
{
    /**
     * The full PCRE-compatible regular expression against which to validate the data.
     *
     * @var string
     */
    protected $regularExpression;

    /**
     * Constructs the validator, initializing the regular expression.
     *
     * @param ContainerBuilder $container    The current service manager instance.
     * @param string         $regularExpression The PCRE regular expression against which to validate the data.
     * @param string         $errorMessage      The error message to return if the data does not match the expression.
     *
     * @throws \InvalidArgumentException Thrown if the regular expression is not valid.
     */
    public function __construct(ContainerBuilder $container, $regularExpression, $errorMessage = null)
    {
        parent::__construct($container, $errorMessage);

        if (!isset($regularExpression) || !is_string($regularExpression) || empty($regularExpression)) {
            throw new \InvalidArgumentException($this->__('An invalid regular expression was recieved.'));
        }

        $this->regularExpression = $regularExpression;
    }

    /**
     * Validates the specified data, ensuring that it is a string value that matches the regular expression pattern.
     *
     * @param mixed $data The data to be validated.
     *
     * @return boolean True if the data is a string value that matches the regular expression pattern; otherwise false.
     */
    public function isValid($data)
    {
        $valid = false;

        if (isset($data)) {
            if (is_string($data)) {
                if (preg_match($this->regularExpression, $data)) {
                    $valid = true;
                }
            }
        }

        return $valid;
    }
}
