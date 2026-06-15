<?php

namespace BarlowsWoodyard\Component\CheckoutLog\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\BaseDatabaseModel;

class LogModel extends BaseDatabaseModel
{
    public function getItem(int $id): ?object
    {
        if ($id <= 0) {
            return null;
        }

        $db    = $this->getDatabase();
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__djc2_checkout_log'))
            ->where($db->quoteName('id') . ' = ' . $id);

        $db->setQuery($query);
        return $db->loadObject() ?: null;
    }
}
