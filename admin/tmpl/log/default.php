<?php

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

/** @var \BarlowsWoodyard\Component\CheckoutLog\Administrator\View\Log\HtmlView $this */

$item = $this->item;

if (!$item) : ?>
    <div class="alert alert-danger"><?= Text::_('JERROR_AN_ERROR_HAS_OCCURRED'); ?></div>
<?php return;
endif;

$opts     = $item->delivery_options_json ? json_decode($item->delivery_options_json, true) : [];
$currency = $item->currency ?: '£';
$isOrder  = $item->event_type === 'order_placed';
?>

<div class="container-fluid">
    <div class="row g-3">

        <!-- Left column: main details -->
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center gap-2">
                    <?php if ($isOrder) : ?>
                        <span class="badge bg-success fs-6"><?= Text::_('COM_CHECKOUTLOG_EVENT_ORDER'); ?></span>
                    <?php else : ?>
                        <span class="badge bg-secondary fs-6"><?= Text::_('COM_CHECKOUTLOG_EVENT_CART'); ?></span>
                    <?php endif; ?>
                    <span class="text-muted small">
                        <?= HTMLHelper::_('date', $item->created_at, 'j F Y H:i:s', null); ?> UTC
                    </span>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <tbody>
                            <tr>
                                <th style="width:190px" class="ps-3"><?= Text::_('COM_CHECKOUTLOG_COL_POSTCODE'); ?></th>
                                <td><?= $item->postcode ? $this->escape($item->postcode) : '<em class="text-muted">—</em>'; ?></td>
                            </tr>
                            <tr>
                                <th class="ps-3"><?= Text::_('COM_CHECKOUTLOG_COL_DISTANCE'); ?></th>
                                <td>
                                    <?php if ($item->distance_text) : ?>
                                        <?= $this->escape($item->distance_text); ?>
                                        &nbsp;/&nbsp;
                                        <?= $this->escape($item->duration_text); ?>
                                        <small class="text-muted">(<?= (float) $item->distance_km; ?> km)</small>
                                    <?php else : ?>
                                        <em class="text-muted">—</em>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-3"><?= Text::_('COM_CHECKOUTLOG_COL_DELIVERY_METHOD'); ?></th>
                                <td><?= $item->delivery_method ? $this->escape($item->delivery_method) : '<em class="text-muted">—</em>'; ?></td>
                            </tr>
                            <?php if ($item->order_number) : ?>
                            <tr>
                                <th class="ps-3"><?= Text::_('COM_CHECKOUTLOG_COL_ORDER'); ?></th>
                                <td>
                                    <a href="<?= Route::_('index.php?option=com_djcatalog2&view=order&layout=edit&id=' . (int) $item->order_id); ?>">
                                        <?= $this->escape($item->order_number); ?>
                                    </a>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="ps-3"><?= Text::_('COM_CHECKOUTLOG_FIELD_SESSION'); ?></th>
                                <td><code style="word-break:break-all"><?= $this->escape($item->session_id); ?></code></td>
                            </tr>
                            <tr>
                                <th class="ps-3"><?= Text::_('COM_CHECKOUTLOG_FIELD_IP'); ?></th>
                                <td><code><?= $this->escape($item->ip_address); ?></code></td>
                            </tr>
                            <tr>
                                <th class="ps-3"><?= Text::_('COM_CHECKOUTLOG_FIELD_USER'); ?></th>
                                <td>
                                    <?php if ($item->user_id) : ?>
                                        <a href="<?= Route::_('index.php?option=com_users&task=user.edit&id=' . (int) $item->user_id); ?>">
                                            <?= Text::sprintf('COM_CHECKOUTLOG_USER_ID', (int) $item->user_id); ?>
                                        </a>
                                    <?php else : ?>
                                        <em class="text-muted"><?= Text::_('COM_CHECKOUTLOG_GUEST'); ?></em>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php if (!empty($opts)) : ?>
            <div class="card mb-3">
                <div class="card-header"><?= Text::_('COM_CHECKOUTLOG_COL_DELIVERY_OPTIONS'); ?></div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($opts as $o) :
                        $selected = !empty($o['selected']); ?>
                        <li class="list-group-item d-flex align-items-center gap-2
                                   <?= $selected ? 'list-group-item-primary fw-bold' : ''; ?>">
                            <?php if ($selected) : ?>
                                <span class="badge bg-primary">✓</span>
                            <?php else : ?>
                                <span class="badge bg-light text-dark">·</span>
                            <?php endif; ?>
                            <?= $this->escape($o['label']); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>

        <!-- Right column: totals -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header"><?= Text::_('COM_CHECKOUTLOG_TOTALS'); ?></div>
                <table class="table table-sm mb-0">
                    <tbody>
                        <tr>
                            <th class="ps-3"><?= Text::_('COM_CHECKOUTLOG_COL_PRODUCTS'); ?></th>
                            <td class="text-end pe-3"><?= $currency . number_format((float) $item->products_total, 2); ?></td>
                        </tr>
                        <tr>
                            <th class="ps-3"><?= Text::_('COM_CHECKOUTLOG_COL_DELIVERY_COST'); ?></th>
                            <td class="text-end pe-3"><?= $currency . number_format((float) $item->delivery_cost, 2); ?></td>
                        </tr>
                        <?php if ((float) $item->payment_cost > 0) : ?>
                        <tr>
                            <th class="ps-3"><?= Text::_('COM_CHECKOUTLOG_FIELD_PAYMENT_COST'); ?></th>
                            <td class="text-end pe-3"><?= $currency . number_format((float) $item->payment_cost, 2); ?></td>
                        </tr>
                        <?php endif; ?>
                        <tr class="table-active">
                            <th class="ps-3 fw-bold"><?= Text::_('COM_CHECKOUTLOG_COL_GRAND'); ?></th>
                            <td class="text-end pe-3 fw-bold fs-5"><?= $currency . number_format((float) $item->grand_total, 2); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
