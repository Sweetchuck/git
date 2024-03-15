<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\CommitMsg;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Sweetchuck\Git\CommitMsg\CommitMsgHandler;
use Sweetchuck\Git\Tests\Unit\TestBase;
use Symfony\Component\Yaml\Yaml;

#[CoversClass(CommitMsgHandler::class)]
class CommitMsgHandlerTest extends TestBase
{

    /**
     * @return array<string, mixed>
     */
    public static function casesParse(): array
    {
        $cases = [];
        $fixturesDir = static::fixturesDir('CommitMsgHandler');
        $dirIterator = new \GlobIterator("$fixturesDir/parse.*.yml");
        while ($dirIterator->valid()) {
            /** @var \SplFileInfo $caseYml */
            $caseYml = $dirIterator->current();
            $caseTxtPathName = preg_replace('/\.yml$/', '.txt', $caseYml->getPathname());
            $caseName = pathinfo($caseYml->getPathname(), \PATHINFO_FILENAME);
            $cases[$caseName] = [
                'expected' => Yaml::parseFile($caseYml->getPathname()),
                'commitMsg' => file_get_contents($caseTxtPathName) ?: '',
            ];

            $dirIterator->next();
        }

        return $cases;
    }

    /**
     * @param array<string, mixed> $expected
     */
    #[DataProvider('casesParse')]
    public function testParse(array $expected, string $commitMsg): void
    {
        $handler = new CommitMsgHandler();
        $parts = $handler->parse($commitMsg);
        static::assertSame($expected, $parts);
        static::assertSame($commitMsg, $handler->render($parts));
    }
}
