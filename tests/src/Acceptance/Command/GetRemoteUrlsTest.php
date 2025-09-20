<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\AddRemoteFetchUrl;
use Sweetchuck\Git\Command\AddRemotePushUrl;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\Command\DeleteRemoteFetchUrl;
use Sweetchuck\Git\Command\DeleteRemotePushUrl;
use Sweetchuck\Git\Command\GetRemoteFetchUrls;
use Sweetchuck\Git\Command\GetRemotePushUrls;
use Sweetchuck\Git\Command\SetRemoteFetchUrl;
use Sweetchuck\Git\Command\SetRemotePushUrl;
use Sweetchuck\Git\CommandFactory;
use Sweetchuck\Git\Repository;

#[CoversClass(GetRemoteFetchUrls::class)]
#[CoversClass(GetRemotePushUrls::class)]
#[CoversClass(SetRemoteFetchUrl::class)]
#[CoversClass(SetRemotePushUrl::class)]
#[CoversClass(AddRemoteFetchUrl::class)]
#[CoversClass(AddRemotePushUrl::class)]
#[CoversClass(DeleteRemoteFetchUrl::class)]
#[CoversClass(DeleteRemotePushUrl::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[Group('command-git-remote')]
class GetRemoteUrlsTest extends CommandTestBase
{
    #[Test]
    public function testExecute(): void
    {
        $projectDir = $this->createTempDirectory();
        $initStepGitInitCommon = [
            'type' => 'exec',
            'command' => <<<'SHELL'
                git init {{ dirSafe }} \
                && cd {{ dirSafe }} \
                && git config user.email "test@example.com" \
                && git config user.name "Test User"
                SHELL,
        ];
        $initSteps = [
            $initStepGitInitCommon,
        ];
        $this->executeSteps($projectDir, $initSteps);

        $commandFactory = new CommandFactory();
        $repo = new Repository($projectDir, $commandFactory);
        $repo->addRemote([
            'name' => 'upstream',
            'url' => 'both-01.git',
        ]);

        static::assertSame(
            [
                'both-01.git',
            ],
            $repo->getRemoteFetchUrls(['remoteName' => 'upstream']),
            'After "git remote add ..." there is only one fetch url',
        );
        static::assertSame(
            [
                'both-01.git'
            ],
            $repo->getRemotePushUrls(['remoteName' => 'upstream']),
            'After "git remote add ..." there is only one push url',
        );

        $repo->setRemoteFetchUrl([
            'remoteName' => 'upstream',
            'url' => 'both-02.git',
            'oldUrl' => 'both-01.git',
        ]);
        // The command above meant to replace only the fetch url, but the push url was also replaced.
        // I think Git intentionally behaves differently when there is only one URL,
        // and fetch and push are the same.
        static::assertSame(
            [
                'both-02.git',
            ],
            $repo->getRemoteFetchUrls(['remoteName' => 'upstream']),
            'After "git remote set-url ..." the fetch url has changed',
        );
        static::assertSame(
            [
                'both-02.git',
            ],
            $repo->getRemotePushUrls(['remoteName' => 'upstream']),
            'After "git remote set-url ..." the push url has changed',
        );

        $repo->setRemotePushUrl([
            'remoteName' => 'upstream',
            'url' => 'push-01.git',
        ]);
        static::assertSame(
            [
                'both-02.git',
            ],
            $repo->getRemoteFetchUrls(['remoteName' => 'upstream']),
            'After "git remote set-url --push ..." the fetch url is untouched',
        );
        static::assertSame(
            [
                'push-01.git',
            ],
            $repo->getRemotePushUrls(['remoteName' => 'upstream']),
            'After "git remote set-url --push ..." the push url has changed',
        );

        $repo->addRemoteFetchUrl([
            'remoteName' => 'upstream',
            'url' => 'fetch-01.git',
        ]);
        static::assertSame(
            [
                'both-02.git',
                'fetch-01.git',
            ],
            $repo->getRemoteFetchUrls(['remoteName' => 'upstream']),
            'After "git remote set-url --add ..." the new url is added',
        );
        static::assertSame(
            [
                'push-01.git',
            ],
            $repo->getRemotePushUrls(['remoteName' => 'upstream']),
            'After "git remote set-url --add ..." push urls are the same',
        );

        $repo->addRemotePushUrl([
            'remoteName' => 'upstream',
            'url' => 'push-02.git',
        ]);
        static::assertSame(
            [
                'both-02.git',
                'fetch-01.git',
            ],
            $repo->getRemoteFetchUrls(['remoteName' => 'upstream']),
            'After "git remote set-url --add --push ..." fetch urls are the same',
        );
        static::assertSame(
            [
                'push-01.git',
                'push-02.git',
            ],
            $repo->getRemotePushUrls(['remoteName' => 'upstream']),
            'After "git remote set-url --add --push ..." the new url is added',
        );

        $repo->deleteRemoteFetchUrl([
            'remoteName' => 'upstream',
            'url' => 'both-02.git',
        ]);
        static::assertSame(
            [
                'fetch-01.git',
            ],
            $repo->getRemoteFetchUrls(['remoteName' => 'upstream']),
            'After "git remote set-url --delete ..." url is removed from the fetch section',
        );
        static::assertSame(
            [
                'push-01.git',
                'push-02.git',
            ],
            $repo->getRemotePushUrls(['remoteName' => 'upstream']),
            'After "git remote set-url --delete ..." push urls are the same',
        );

        $repo->deleteRemotePushUrl([
            'remoteName' => 'upstream',
            'url' => 'push-01.git',
        ]);
        static::assertSame(
            [
                'fetch-01.git',
            ],
            $repo->getRemoteFetchUrls(['remoteName' => 'upstream']),
            'After "git remote set-url --delete --push ..." fetch urls are the same',
        );
        static::assertSame(
            [
                'push-02.git',
            ],
            $repo->getRemotePushUrls(['remoteName' => 'upstream']),
            'After "git remote set-url --delete ..." url is removed from the push section',
        );
    }
}
