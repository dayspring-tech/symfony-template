<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1458079368.
 * Generated on 2016-03-15 22:02:48 by vagrant
 */
class PropelMigration_1458079368
{

    public function preUp($manager)
    {
        // add the pre-migration code here
    }

    public function postUp($manager)
    {
        // add the post-migration code here
    }

    public function preDown($manager)
    {
        // add the pre-migration code here
    }

    public function postDown($manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

DROP INDEX `users_I_1` ON `users`;

ALTER TABLE `users`
    ADD `reset_token` CHAR(40) AFTER `salt`,
    ADD `reset_token_expire` DATE AFTER `reset_token`;

CREATE UNIQUE INDEX `user_username_UNIQUE` ON `users` (`username`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

DROP INDEX `user_username_UNIQUE` ON `users`;

ALTER TABLE `users` DROP `reset_token`;

ALTER TABLE `users` DROP `reset_token_expire`;

CREATE INDEX `users_I_1` ON `users` (`username`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}