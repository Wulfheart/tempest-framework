<?php

declare(strict_types=1);

namespace Tempest\Console\Commands;

use Tempest\AppConfig;
use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;
use Tempest\Database\Migrations\MigrationManager;
use Tempest\Database\Migrations\MigrationMigrated;
use Tempest\Events\EventHandler;

final class MigrateCommand
{
    private static int $count = 0;

    public function __construct(
        private readonly Console $console,
        private readonly MigrationManager $migrationManager,
        private readonly AppConfig $config,
    ) {
    }

    #[ConsoleCommand(
        name: 'migrate',
        description: 'Run all new migrations',
    )]
    public function __invoke(bool $force = false): void
    {
        if (! $force
            && $this->config->environment->isProduction()
            && ! $this->console->confirm("You are running in production. Are you sure you want to continue?")
        ) {
            return;
        }

        $this->migrationManager->up();

        $this->console->success("Done");
        $this->console->writeln(sprintf("Migrated %s migrations", self::$count));
    }

    #[EventHandler]
    public function onMigrationMigrated(MigrationMigrated $event): void
    {
        $this->console->writeln("- {$event->name}");
        self::$count += 1;
    }
}
