<?php

declare(strict_types=1);

namespace JVE\JvRanking\Updates;

use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\AbstractListTypeToCTypeUpdate;

#[UpgradeWizard('jveJvRankingCTypeMigration')]
final class JVEJvRankingCTypeMigration extends AbstractListTypeToCTypeUpdate
{
    public function getTitle(): string
    {
        return 'Migrate "JVE JvRanking" plugins to content elements.';
    }

    public function getDescription(): string
    {
        return 'The "JVE JvRanking" plugins are now registered as content element. Update migrates existing records and backend user permissions.';
    }

    /**
     * This must return an array containing the "list_type" to "CType" mapping
     *
     *  Example:
     *
     *  [
     *      'pi_plugin1' => 'pi_plugin1',
     *      'pi_plugin2' => 'new_content_element',
     *  ]
     *
     * @return array<string, string>
     */
    protected function getListTypeToCTypeMapping(): array
    {
        return [
             'jvranking_pi1' => 'jvranking_pi1'
        ];
    }
}
