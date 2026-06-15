<?php

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

/** @var \BarlowsWoodyard\Component\CheckoutLog\Administrator\View\Logs\HtmlView $this */

$state    = $this->state;
$currency = '£';
?>

<form action="<?= Route::_('index.php?option=com_checkoutlog&view=logs'); ?>"
      method="post" name="adminForm" id="adminForm">

    <!-- Filter bar -->
    <div class="container-fluid mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label for="filter_search" class="visually-hidden"><?= Text::_('JSEARCH_FILTER'); ?></label>
                <div class="input-group">
                    <input type="text" name="filter_search" id="filter_search"
                           class="form-control"
                           placeholder="<?= Text::_('COM_CHECKOUTLOG_SEARCH_PLACEHOLDER'); ?>"
                           value="<?= $this->escape($state->get('filter.search')); ?>">
                    <button class="btn btn-primary" type="submit">
                        <span class="icon-search" aria-hidden="true"></span>
                        <?= Text::_('JSEARCH_FILTER'); ?>
                    </button>
                </div>
            </div>
            <div class="col-auto">
                <select name="filter_event_type" class="form-select" onchange="this.form.submit()">
                    <option value=""><?= Text::_('COM_CHECKOUTLOG_FILTER_ALL_EVENTS'); ?></option>
                    <?php foreach (['cart_summary', 'order_placed'] as $et) : ?>
                        <option value="<?= $et; ?>" <?= $state->get('filter.event_type') === $et ? 'selected' : ''; ?>>
                            <?= $et; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-auto">
                <input type="date" name="filter_date_from" class="form-control"
                       value="<?= $this->escape($state->get('filter.date_from')); ?>"
                       title="<?= Text::_('COM_CHECKOUTLOG_FILTER_DATE_FROM'); ?>"
                       onchange="this.form.submit()">
            </div>
            <div class="col-auto">
                <input type="date" name="filter_date_to" class="form-control"
                       value="<?= $this->escape($state->get('filter.date_to')); ?>"
                       title="<?= Text::_('COM_CHECKOUTLOG_FILTER_DATE_TO'); ?>"
                       onchange="this.form.submit()">
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-outline-secondary" onclick="
                        document.getElementById('filter_search').value = '';
                        document.querySelector('[name=filter_event_type]').value = '';
                        document.querySelector('[name=filter_date_from]').value = '';
                        document.querySelector('[name=filter_date_to]').value = '';
                        document.getElementById('adminForm').submit();">
                    <?= Text::_('JSEARCH_FILTER_CLEAR'); ?>
                </button>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <table class="table table-striped table-hover table-sm" id="checkoutLogList">
            <thead class="table-dark">
                <tr>
                    <th scope="col" style="width:50px">#</th>
                    <th scope="col" style="width:130px"><?= Text::_('COM_CHECKOUTLOG_COL_CREATED'); ?></th>
                    <th scope="col" style="width:110px"><?= Text::_('COM_CHECKOUTLOG_COL_EVENT'); ?></th>
                    <th scope="col" style="width:95px"><?= Text::_('COM_CHECKOUTLOG_COL_POSTCODE'); ?></th>
                    <th scope="col" style="width:120px"><?= Text::_('COM_CHECKOUTLOG_COL_DISTANCE'); ?></th>
                    <th scope="col"><?= Text::_('COM_CHECKOUTLOG_COL_DELIVERY_OPTIONS'); ?></th>
                    <th scope="col" style="width:80px" class="text-end"><?= Text::_('COM_CHECKOUTLOG_COL_PRODUCTS'); ?></th>
                    <th scope="col" style="width:80px" class="text-end"><?= Text::_('COM_CHECKOUTLOG_COL_DELIVERY_COST'); ?></th>
                    <th scope="col" style="width:80px" class="text-end"><?= Text::_('COM_CHECKOUTLOG_COL_GRAND'); ?></th>
                    <th scope="col" style="width:110px"><?= Text::_('COM_CHECKOUTLOG_COL_ORDER'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($this->items)) : ?>
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">
                            <?= Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                        </td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($this->items as $item) : ?>
                        <?php $opts = $item->delivery_options_json
                            ? json_decode($item->delivery_options_json, true)
                            : []; ?>
                        <tr>
                            <td>
                                <a href="<?= Route::_('index.php?option=com_checkoutlog&view=log&id=' . $item->id); ?>">
                                    <?= (int) $item->id; ?>
                                </a>
                            </td>
                            <td>
                                <span><?= HTMLHelper::_('date', $item->created_at, 'j M Y', null); ?></span><br>
                                <small class="text-muted"><?= HTMLHelper::_('date', $item->created_at, 'H:i:s', null); ?> UTC</small>
                            </td>
                            <td>
                                <?php if ($item->event_type === 'order_placed') : ?>
                                    <span class="badge bg-success"><?= Text::_('COM_CHECKOUTLOG_EVENT_ORDER'); ?></span>
                                <?php else : ?>
                                    <span class="badge bg-secondary"><?= Text::_('COM_CHECKOUTLOG_EVENT_CART'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= $this->escape($item->postcode ?: '—'); ?></td>
                            <td>
                                <?php if ($item->distance_text) : ?>
                                    <small><?= $this->escape($item->distance_text); ?></small><br>
                                    <small class="text-muted"><?= $this->escape($item->duration_text); ?></small>
                                <?php else : ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($opts)) : ?>
                                    <details>
                                        <summary class="text-muted" style="cursor:pointer;font-size:.8rem">
                                            <?= count($opts); ?> <?= Text::_('COM_CHECKOUTLOG_OPTIONS_LABEL'); ?>
                                            <?php foreach ($opts as $o) : if (!empty($o['selected'])) : ?>
                                                — <strong><?= $this->escape($o['label']); ?></strong>
                                            <?php break; endif; endforeach; ?>
                                        </summary>
                                        <div class="mt-1" style="font-size:.8rem">
                                            <?php foreach ($opts as $o) : ?>
                                                <div class="<?= !empty($o['selected']) ? 'fw-bold text-primary' : 'text-muted'; ?>">
                                                    <?= !empty($o['selected']) ? '✓' : '·'; ?>
                                                    <?= $this->escape($o['label']); ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </details>
                                <?php else : ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end"><?= $currency . number_format((float) $item->products_total, 2); ?></td>
                            <td class="text-end"><?= $currency . number_format((float) $item->delivery_cost, 2); ?></td>
                            <td class="text-end fw-bold"><?= $currency . number_format((float) $item->grand_total, 2); ?></td>
                            <td>
                                <?php if ($item->order_number) : ?>
                                    <a href="<?= Route::_('index.php?option=com_djcatalog2&view=order&layout=edit&id=' . (int) $item->order_id); ?>">
                                        <?= $this->escape($item->order_number); ?>
                                    </a>
                                <?php else : ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <?= $this->pagination->getListFooter(); ?>
    </div>

    <input type="hidden" name="task" value="">
    <input type="hidden" name="boxchecked" value="0">
    <?= HTMLHelper::_('form.token'); ?>
</form>
