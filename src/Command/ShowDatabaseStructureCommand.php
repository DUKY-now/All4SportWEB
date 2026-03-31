<?php

namespace App\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'db:show-structure',
    description: 'Affiche la structure complète de la base de données'
)]
class ShowDatabaseStructureCommand extends Command
{
    public function __construct(private Connection $connection)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('📊 STRUCTURE DE LA BASE DE DONNÉES');

        // Récupérer le nom de la base de données
        $dbName = $this->connection->getDatabase();
        $io->section("Base de données: <fg=cyan>{$dbName}</>");

        // Afficher les tables
        $sql = "SELECT TABLE_NAME, TABLE_TYPE FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = :dbName ORDER BY TABLE_NAME";
        $tables = $this->connection->fetchAllAssociative($sql, ['dbName' => $dbName]);

        if (empty($tables)) {
            $io->error('Aucune table trouvée!');
            return Command::FAILURE;
        }

        foreach ($tables as $table) {
            $tableName = $table['TABLE_NAME'];
            $io->writeln("\n<info>═══════════════════════════════════════</info>");
            $io->writeln("<info>📋 TABLE: <fg=cyan>{$tableName}</></info>");
            $io->writeln("<info>═══════════════════════════════════════</info>");

            // Récupérer les colonnes
            $columnsSql = "SELECT COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE, COLUMN_KEY, COLUMN_DEFAULT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = :dbName AND TABLE_NAME = :tableName";
            $columns = $this->connection->fetchAllAssociative($columnsSql, [
                'dbName' => $dbName,
                'tableName' => $tableName
            ]);

            $rows = [];
            foreach ($columns as $col) {
                $isPK = $col['COLUMN_KEY'] === 'PRI' ? '🔑' : '';
                $isFK = $col['COLUMN_KEY'] === 'MUL' ? '🔗' : '';
                $isUnique = $col['COLUMN_KEY'] === 'UNI' ? '🔐' : '';
                $nullable = $col['IS_NULLABLE'] === 'YES' ? '✅' : '❌';

                $rows[] = [
                    $col['COLUMN_NAME'],
                    $col['COLUMN_TYPE'],
                    $nullable,
                    $col['COLUMN_DEFAULT'] ?? 'NULL',
                    $isPK . $isFK . $isUnique
                ];
            }

            $io->table(
                ['Colonne', 'Type', 'Nullable', 'Default', 'Clés'],
                $rows
            );

            // Afficher les clés étrangères
            $fkSql = "SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
                     FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                     WHERE TABLE_SCHEMA = :dbName AND TABLE_NAME = :tableName AND REFERENCED_TABLE_NAME IS NOT NULL";
            $foreignKeys = $this->connection->fetchAllAssociative($fkSql, [
                'dbName' => $dbName,
                'tableName' => $tableName
            ]);

            if (!empty($foreignKeys)) {
                $io->writeln("\n<fg=yellow>🔗 CLÉS ÉTRANGÈRES:</>");
                foreach ($foreignKeys as $fk) {
                    $io->writeln("   <fg=green>{$fk['COLUMN_NAME']}</> → <fg=cyan>{$fk['REFERENCED_TABLE_NAME']}({$fk['REFERENCED_COLUMN_NAME']})</>");
                }
            }
        }

        $io->success("✅ Total de tables: " . count($tables));
        return Command::SUCCESS;
    }
}
