<?php

/*
 * This file is part of the OverblogGraphQLBundle package.
 *
 * (c) Overblog <http://github.com/overblog/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Overblog\GraphQLBundle\Executor;

use GraphQL\Executor\ExecutionResult;
use GraphQL\Executor\Promise\Promise;
use GraphQL\Schema;
use Overblog\GraphQLBundle\Executor\Promise\PromiseAdapterInterface;

class Executor implements ExecutorInterface
{
    /**
     * @param Schema      $schema
     * @param string      $requestString
     * @param null|array  $rootValue
     * @param null|array  $contextValue
     * @param null|array  $variableValues
     * @param null|string $operationName
     *
     * @return ExecutionResult|Promise
     */
    public function execute(Schema $schema, $requestString, $rootValue = null, $contextValue = null, $variableValues = null, $operationName = null)
    {
        return call_user_func_array('GraphQL\GraphQL::executeAndReturnResult', func_get_args());
    }

    /**
     * @param PromiseAdapterInterface|null $promiseAdapter
     */
    public function setPromiseAdapter(PromiseAdapterInterface $promiseAdapter = null)
    {
        call_user_func_array('GraphQL\GraphQL::setPromiseAdapter', func_get_args());
    }
}
