<?php

namespace BarlowsWoodyard\Component\CheckoutLog\Administrator\View\Logs;

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

class HtmlView extends BaseHtmlView
{
    /** @var object[] */
    protected $items;

    /** @var \Joomla\CMS\Pagination\Pagination */
    protected $pagination;

    /** @var \Joomla\Registry\Registry */
    protected $state;

    public function display($tpl = null): void
    {
        $this->items      = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state      = $this->get('State');

        ToolbarHelper::title(Text::_('COM_CHECKOUTLOG_LOGS_TITLE'), 'list-2');

        parent::display($tpl);
    }
}
