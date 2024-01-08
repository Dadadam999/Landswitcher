<?php

declare(strict_types=1);

namespace Plugin\Landswitcher\Migrations;

use JTL\Plugin\Migration;
use JTL\Update\IMigration;

/**
 * Class Migration20240106190725
 * @package Plugin\Landswitcher\Migrations
 */
class Migration20240106190725 extends Migration implements IMigration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->execute(
            'CREATE TABLE IF NOT EXISTS `landswitcher_redirects` (
                  `redirectID` INT(11) NOT NULL AUTO_INCREMENT,
                  `cISO` VARCHAR(5) NOT NULL,
                  `url` VARCHAR(255) NOT NULL,
                  PRIMARY KEY (`redirectID`),
                  INDEX `fk_country` (`cISO`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        if ($this->doDeleteData()) {
            $this->execute('DROP TABLE IF EXISTS `landswitcher_redirects`');
        }
    }
}
