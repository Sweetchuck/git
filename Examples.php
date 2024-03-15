<?php

declare(strict_types = 1);

use Consolidation\AnnotatedCommand\Attributes\Argument as CliArgument;
use Consolidation\AnnotatedCommand\Attributes\Command as CliCommand;
use Consolidation\AnnotatedCommand\Attributes\DefaultTableFields as CliDefaultTableFields;
use Consolidation\AnnotatedCommand\Attributes\FieldLabels as CliFieldLabels;
use Consolidation\AnnotatedCommand\Attributes\FilterDefaultField as CliFilterDefaultField;
use Consolidation\AnnotatedCommand\Attributes\Help as CliHelp;
use Consolidation\AnnotatedCommand\Attributes\Hook as CliHook;
use Consolidation\AnnotatedCommand\Attributes\Option as CliOption;
use Consolidation\AnnotatedCommand\Attributes\Usage as CliUsage;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Robo\Collection\CallableTask;
use Robo\Contract\TaskInterface;
use Robo\Tasks;
use Sweetchuck\Git\Command\GetBranches;
use Sweetchuck\Git\Command\GetStatus;
use Sweetchuck\Git\Command\InitRepository;
use Sweetchuck\Git\CommandFactory;
use Sweetchuck\Git\Repository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

/**
 * ./vendor/bin/robo --load-from='Examples.php'
 *
 * @phpstan-import-type PhpExecutable from \Sweetchuck\Git\Tests\Phpstan
 */
class Examples extends Tasks implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    protected Filesystem $fs;

    public function __construct()
    {
        $this->fs = new Filesystem();
    }

    #[CliCommand(name: 'get:branches')]
    #[CliHelp(
        description: 'Example usage of GetBranches command.',
        synopsis: <<< 'TEXT'
            Expected output:
            [
                "branches" => [
                    "refs/heads/1.x" => [
                        "isCurrentBranch" => false,
                        "push" => null,
                        "push.short" => null,
                        "refName" => "refs/heads/1.x",
                        "refName.short" => "1.x"
                        "track" => null,
                        "track.ahead" => null,
                        "track.behind" => null,
                        "track.gone" => false,
                        "upstream" => null,
                        "upstream.short" => null,
                    ],
                    "refs/heads/2.x" => [
                        "isCurrentBranch" => true,
                        "push" => null,
                        "push.short" => null,
                        "refName" => "refs/heads/2.x",
                        "refName.short" => "2.x",
                        "track" => null,
                        "track.ahead" => null,
                        "track.behind" => null,
                        "track.gone" => false,
                        "upstream" => null,
                        "upstream.short" => null,
                    ],
                    "refs/heads/3.x" => [
                        "isCurrentBranch" => false,
                        "push" => null,
                        "push.short" => null,
                        "refName" => "refs/heads/3.x",
                        "refName.short" => "3.x",
                        "track" => null,
                        "track.ahead" => null,
                        "track.behind" => null,
                        "track.gone" => false,
                        "upstream" => null,
                        "upstream.short" => null,
                    ],
                ],
                "currentBranch" => "refs/heads/2.x",
            ]
            TEXT,
    )]
    public function cmdGetBranchesExecute(): TaskInterface
    {
        $cb = $this->collectionBuilder();
        $cb->setProgressIndicator(null);

        $prepareCommand = <<<'SHELL'
            git init --initial-branch='1.x'
            touch README.md
            git add README.md
            git commit --message='Initial commit'
            git switch --create='2.x'
            git switch --create='3.x'
            git switch '2.x'
            SHELL;

        $main = new CallableTask(
            function (): int {
                $command = new GetBranches();
                $result = $command->execute();
                dump($result->artifacts);

                return 0;
            },
            $cb,
        );

        $cb->addTaskList([
            // @phpstan-ignore-next-line
            'tmpDir' => $this->taskTmpDir()->cwd(),
            'prepare' => $this->taskExec($prepareCommand),
            'main' => $main,
        ]);

        return $cb;
    }

    #[CliCommand(name: 'get:status')]
    #[CliHelp(
        description: 'Example usage of GetStatus command.',
        synopsis: <<< 'TEXT'
            Expected output:
            [
                "README.md" => " M",
                "deleted.txt" => "D ",
                "modified-both.txt" => " M",
                "new-file.txt" => "??",
            ]
            TEXT,
    )]
    public function cmdGetStatusExecute(): TaskInterface
    {
        $cb = $this->collectionBuilder();
        $cb->setProgressIndicator(null);

        $prepareCommand = <<<'SHELL'
            git init --initial-branch='main'
            touch README.md
            git add README.md
            git commit --message='Initial commit'

            # Create a modified file (unstaged)
            echo "Modified content" >> README.md

            # Create an untracked file
            touch new-file.txt

            # Create a modified file (staged)
            touch modified-staged.txt
            git add modified-staged.txt
            git commit --message='Add modified-staged.txt'
            echo "Modified content" > modified-staged.txt
            git add modified-staged.txt

            # Create a file modified both in staged and working copy
            touch modified-both.txt
            git add modified-both.txt
            git commit --message='Add modified-both.txt'
            echo "Staged modification" > modified-both.txt
            git add modified-both.txt
            echo "Unstaged modification" >> modified-both.txt

            # Create a deleted file
            touch deleted.txt
            git add deleted.txt
            git commit --message='Add deleted.txt'
            git rm deleted.txt
            SHELL;

        $main = new CallableTask(
            function (): int {
                $command = new GetStatus();
                $result = $command->execute();
                dump($result->artifacts);

                return 0;
            },
            $cb,
        );

        $cb->addTaskList([
            // @phpstan-ignore-next-line
            'tmpDir' => $this->taskTmpDir()->cwd(),
            'prepare' => $this->taskExec($prepareCommand),
            'main' => $main,
        ]);

        return $cb;
    }

    #[CliCommand(name: 'run:repository:scenario01')]
    public function cmdRepositoryScenario01(): TaskInterface
    {
        $cb = $this->collectionBuilder();
        $cb->setProgressIndicator(null);

        $main = new CallableTask(
            function (): int {
                $result = (new InitRepository())
                    ->setInitialBranch('1.x')
                    ->execute();
                $repository = new Repository(
                    Path::getDirectory($result->artifacts['gitDir']),
                    new CommandFactory(),
                );
                dump($repository->getStatus());

                return 0;
            },
            $cb,
        );

        $cb->addTaskList([
            // @phpstan-ignore-next-line
            'tmpDir' => $this->taskTmpDir()->cwd(),
            'main' => $main,
        ]);

        return $cb;
    }
}
