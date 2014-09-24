<?php
use yii\db\Migration;

/**
 * @inheritdoc
 * @SuppressWarnings(ShortMethodName)
 * @SuppressWarnings(CamelCaseClassName)
 */
class m140818_132022_state_init extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        // TODO: proper migrations
        $sql = "CREATE TABLE `ym_util_state` (
          `key` varchar(255) NOT NULL PRIMARY KEY,
          `value` varchar(255) NOT NULL,
          `timestamp` datetime NOT NULL
        ) COMMENT='Contains system state variables' ENGINE='InnoDB' COLLATE 'utf8_unicode_ci';";

        $this->execute($sql);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->delete('ym_util_state');
        return true;
    }
}
