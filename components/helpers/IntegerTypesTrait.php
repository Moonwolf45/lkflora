<?php

namespace app\components\helpers;

use yii\db\ColumnSchemaBuilder;

/**
 * Trait IntegerTypesTrait
 * @package common\components\helpers
 */
trait IntegerTypesTrait
{
    /**
     * Creates a tiny integer column.
     *
     * @param int $length
     *
     * @return ColumnSchemaBuilder the column instance which can be further customized.
     * @throws \yii\base\NotSupportedException
     */
    public function tinyInteger($length = 1)
    {
        return $this->getDb()->getSchema()->createColumnSchemaBuilder('tinyint', $length);
    }

    /**
     * @return \yii\db\Connection the database connection to be used for schema building.
     */
    protected abstract function getDb();
}