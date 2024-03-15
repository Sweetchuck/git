# How contribute


## Steps to create a new command

1. Check if all the options are covered with an Option*Trait
2. If there is no suitable option trait, then create a new one. \
   In rear cases maybe a new type has to be implemented in the CliCommandBuilder.
3. Create a new Command class. Use the option traits. \
   Maybe custom methods also needed to cover custom arguments. \
   Name of the new command should be `<Verb><Subject>`, for example `RenameRemote`.
4. Create a PhpStan typeAlias for the $properties parameter for the new NewCommand::setProperties().
5. Create Unit tests. Use the \Sweetchuck\Git\Tests\Unit\Command\CommandTestBase as a base class.
6. Create Acceptance tests. Use the \Sweetchuck\Git\Tests\Acceptance\Command\CommandTestBase as a base class.
7. Add the new command to the \Sweetchuck\Git\CommandFactoryInterface and CommandFactory class.
8. Add the new command to the \Sweetchuck\Git\Repository class.
9. Modify the status of the covered CLI commands in the README.md under the "Supported commands" section.
