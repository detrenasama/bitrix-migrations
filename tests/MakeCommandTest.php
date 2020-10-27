<?php

namespace Detrena\Tests\BitrixMigrations;

use Mockery as m;

class MakeCommandTest extends CommandTestCase
{
    protected function mockCommand($migrator)
    {
        return m::mock('Detrena\BitrixMigrations\Commands\MakeCommand[abort, info, message, getMigrationObjectByFileName]', [$migrator])
            ->shouldAllowMockingProtectedMethods();
    }

    public function testItCreatesAMigrationFile()
    {
        $migrator = m::mock('Detrena\BitrixMigrations\Migrator');
        $migrator->shouldReceive('createMigration')->once()->andReturn('2015_11_26_162220_bar');

        $command = $this->mockCommand($migrator);
        $command->shouldReceive('message')->once();

        $this->runCommand($command, ['name' => 'test_migration']);
    }
}
