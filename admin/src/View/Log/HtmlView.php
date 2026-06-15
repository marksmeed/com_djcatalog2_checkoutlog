<?php

namespace BarlowsWoodyard\Component\CheckoutLog\Administrator\View\Log;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

class HtmlView extends BaseHtmlView
{
    /** @var object|null */
    protected $item;

    public function display($tpl = null): void
    {
        $id         = Factory::getApplication()->getInput()->getInt('id', 0);
        $this->item = $this->getModel()->getItem($id);

        $title = Text::_('COM_CHECKOUTLOG_LOG_TITLE');
        if ($this->item) {
            $title .= ' #' . $this->item->id;
        }
        ToolbarHelper::title($title, 'list-2');

        $bar = Toolbar::getInstance('toolbar');
        $bar->appendButton(
            'Link',
            'arrow-left-3',
            'JTOOLBAR_BACK',
            Route::_('index.php?option=com_checkoutlog&view=logs')
        );

        parent::display($tpl);
    }
}
