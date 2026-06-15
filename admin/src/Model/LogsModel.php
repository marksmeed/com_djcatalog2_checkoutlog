<?php

namespace BarlowsWoodyard\Component\CheckoutLog\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;

class LogsModel extends ListModel
{
    protected $context = 'com_checkoutlog.logs';

    public function __construct($config = [])
    {
        $config['filter_fields'] = ['id', 'event_type', 'postcode', 'created_at', 'grand_total'];
        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $db    = $this->getDatabase();
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__djc2_checkout_log'));

        $eventType = $this->getState('filter.event_type');
        if ($eventType !== '' && $eventType !== null) {
            $query->where($db->quoteName('event_type') . ' = ' . $db->quote($eventType));
        }

        $search = $this->getState('filter.search');
        if ($search !== '' && $search !== null) {
            $like = $db->quote('%' . $db->escape($search, true) . '%');
            $query->where(
                '(' . $db->quoteName('postcode')     . ' LIKE ' . $like .
                ' OR ' . $db->quoteName('order_number') . ' LIKE ' . $like .
                ' OR ' . $db->quoteName('session_id')   . ' LIKE ' . $like .
                ' OR ' . $db->quoteName('ip_address')   . ' LIKE ' . $like . ')'
            );
        }

        $dateFrom = $this->getState('filter.date_from');
        if ($dateFrom) {
            $query->where($db->quoteName('created_at') . ' >= ' . $db->quote($dateFrom . ' 00:00:00'));
        }

        $dateTo = $this->getState('filter.date_to');
        if ($dateTo) {
            $query->where($db->quoteName('created_at') . ' <= ' . $db->quote($dateTo . ' 23:59:59'));
        }

        $ordering  = $this->getState('list.ordering', 'id');
        $direction = $this->getState('list.direction', 'DESC');
        $query->order($db->quoteName($ordering) . ' ' . $db->escape($direction));

        return $query;
    }

    protected function populateState($ordering = 'id', $direction = 'DESC')
    {
        $app = Factory::getApplication();

        $this->setState('filter.search',
            $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search', ''));
        $this->setState('filter.event_type',
            $app->getUserStateFromRequest($this->context . '.filter.event_type', 'filter_event_type', ''));
        $this->setState('filter.date_from',
            $app->getUserStateFromRequest($this->context . '.filter.date_from', 'filter_date_from', ''));
        $this->setState('filter.date_to',
            $app->getUserStateFromRequest($this->context . '.filter.date_to', 'filter_date_to', ''));

        parent::populateState($ordering, $direction);
    }
}
