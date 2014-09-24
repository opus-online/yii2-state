<?php
/**
 *
 * @author Ivo Kund <ivo@opus.ee>
 * @date 18.08.2014
 */

namespace opus\state;

use yii\db\Connection;
use yii\helpers\ArrayHelper;

/**
 * State handler that stores state values in mysql
 *
 * @author Ivo Kund <ivo@opus.ee>
 * @package opus\state
 */
class MysqlState extends AbstractState
{
    /**
     * Name of the application db component
     *
     * @var string
     */
    public $db = 'db';

    /**
     * @var string
     */
    public $tableName = 'ym_util_state';

    /**
     * Reloads the system states from the database
     */
    public function loadStates()
    {
        $sql = "SELECT `key`, `value` FROM `{$this->tableName}`";
        $items = $this->getDb()->createCommand($sql)->queryAll();

        $items = ArrayHelper::index($items, 'key');
        foreach ($items as $key => $value) {
            $value = unserialize($value['value']);
            $this->map[$key] = $value;
        }

        $this->updateCache();
    }

    /**
     * Stores a setting in the database
     *
     * @param string $name
     * @param string $value
     * @throws \yii\db\Exception
     * @return bool
     */
    protected function setState($name, $value)
    {
        $sql = "REPLACE INTO `{$this->tableName}`  SET `key` = :key, `value` = :value, `timestamp` = NOW()";
        $params = [
            ':key' => $name,
            ':value' => serialize($value)
        ];

        $rows = $this->getDb()->createCommand($sql, $params)->execute();

        return $rows === 1;
    }

    /**
     * @param string $name
     * @throws \yii\db\Exception
     * @return bool
     */
    protected function deleteState($name)
    {
        $sql = "DELETE FROM `{$this->tableName}` WHERE `{$this->nameAttribute}` = :val";
        $params = [':val' => $name];

        $numRows = $this->getDb()->createCommand($sql, $params)->execute();

        $success = $numRows === 1;
        if (true === $success) {
            $this->loadStates();
            return $success;
        }

        return $success;
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @return Connection
     */
    private function getDb()
    {
        return \Yii::$app->get($this->db);
    }
}
