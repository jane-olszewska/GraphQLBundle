<?php

/*
 * This file is part of the OverblogGraphQLBundle package.
 *
 * (c) Overblog <http://github.com/overblog/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Overblog\GraphQLBundle\Command;

use GraphQL\Type\Introspection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GraphQLDumpSchemaCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('graphql:dump-schema')
            ->setAliases(['graph:dump-schema'])
            ->setDescription('Dumps GraphQL schema')
            ->addOption(
                'file',
                null,
                InputOption::VALUE_OPTIONAL,
                'Path to generate schema file.'
            )
            ->addOption(
                'schema',
                null,
                InputOption::VALUE_OPTIONAL,
                'The schema name to generate.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output = new SymfonyStyle($input, $output);

        $request = [
            'query' => Introspection::getIntrospectionQuery(false),
            'variables' => [],
            'operationName' => null,
        ];
        $schemaName = $input->getOption('schema');

        $container = $this->getContainer();
        $result = $container
            ->get('overblog_graphql.request_executor')
            ->execute($request, [], $schemaName)
            ->toArray();

        $file = $input->getOption('file') ?: $container->getParameter('kernel.root_dir').sprintf('/../var/schema%s.json', $schemaName ? '.'.$schemaName : '');

        $schema = json_encode($result['data']);

        file_put_contents($file, $schema);

        $output->success(sprintf('GraphQL schema "%s" was successfully dumped.', realpath($file)));
    }
}
