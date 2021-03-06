<?php
/**
 *
 * Description
 *
 * @package    VirtueMart
 * @subpackage
 * @author
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: orders.php 6408 2012-09-08 11:23:40Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined ('_JEXEC') or die();
?>

	<div id="resultscounter"><?php echo $this->pagination->getResultsCounter (); ?></div>
	<table class="table table-striped">
		<thead>
		<tr>
			<th>
				<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
			</th>
			<th><?php echo $this->sort ('order_number', 'COM_VIRTUEMART_ORDER_LIST_NUMBER')  ?></th>
			<th><?php echo $this->sort ('order_name', 'COM_VIRTUEMART_ORDER_PRINT_NAME')  ?></th>
			<th><?php echo $this->sort ('order_email', 'COM_VIRTUEMART_EMAIL')  ?></th>
			<th><?php echo $this->sort ('payment_method', 'COM_VIRTUEMART_ORDER_PRINT_PAYMENT_LBL')  ?></th>
			<th align="center" width="1%" class="nowrap center text-center"><span class="hidden-phone"><?php echo JText::_ ('COM_VIRTUEMART_PRINT_VIEW'); ?></span>
				<span><i class="visible-phone icon icon-print"></i></span></th>
			<th ><?php echo $this->sort ('created_on', 'COM_VIRTUEMART_ORDER_CDATE')  ?></th>
			<th><?php echo $this->sort ('modified_on', 'COM_VIRTUEMART_ORDER_LIST_MDATE')  ?></th>
			<th><?php echo $this->sort ('order_status', 'COM_VIRTUEMART_STATUS')  ?></th>
			<th><?php echo $this->sort ('order_total', 'COM_VIRTUEMART_TOTAL')  ?></th>
			<th class="visible-desktop"><?php echo $this->sort ('virtuemart_order_id', 'COM_VIRTUEMART_ID')  ?></th>

		</tr>
		</thead>
		<tbody>
		<?php
		if (count ($this->orderslist) > 0) {
			$i = 0;
	
			$keyword = JRequest::getWord ('keyword');

			foreach ($this->orderslist as $key => $order) {
				$checked = JHTML::_ ('grid.id', $i, $order->virtuemart_vendor_id);
				$canDo = $this->canChange($order->created_by) ;
				?>
			<tr >
				<!-- Checkbox -->
				<td><?php echo $checked; ?></td>
				<!-- Order id -->
				<td>
					<?php echo $this->editLink(	$order->virtuemart_order_id, $order->order_number, 'virtuemart_order_id',
						array('class'=> 'hasTooltip', 'title' => JText::_ ('COM_VIRTUEMART_ORDER_EDIT_ORDER_NUMBER') . ' ' .  $order->order_number) );
					?>
				</td>
				<td>
					<?php
					if ($order->virtuemart_user_id) {
						echo $this->editLink(	$order->virtuemart_user_id,	$order->order_name, 'virtuemart_user_id',
							array('class'=> 'hasTooltip', 'title' => JText::_ ('COM_VIRTUEMART_ORDER_EDIT_USER') . ' ' .  $order->order_name) ,'user');
					} else {
						echo $order->order_name;
					}
					?>
				</td>
				<td class="autosize">
					<?php if ($order->order_email) { ?>
						<a href="mailto:<?php echo $order->order_email ?>?subject=<?php echo jText::_('COM_VIRTUEMART_ORDER_LIST_NUMBER') .' '. $order->order_number?>&body=new" target="_top"><i class="icon-envelope"></i></a>
					<?php } ?>
				</td>
				<!-- Payment method -->
				<td><?php echo $order->payment_method; ?></td>
				<!-- Print view -->
				<?php
				/* Print view URL */
				$print_url = juri::root () . 'index.php?option=com_virtuemart&view=invoice&layout=invoice&tmpl=component&virtuemart_order_id=' . $order->virtuemart_order_id . '&order_number=' . $order->order_number . '&order_pass=' . $order->order_pass;
				$print_link = "<a class='btn hasTooltip' title='" . JText::_ ('COM_VIRTUEMART_PRINT') . "' href=\"javascript:void window.open('$print_url', 'win2', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no');\"  >";
				$print_link .= '<span class="icon icon-print" ></span></a>';
				$invoice_link = '';

				if (!$order->invoiceNumber) {
					$invoice_url = juri::root () . 'index.php?option=com_virtuemart&view=invoice&layout=invoice&format=pdf&virtuemart_order_id=' . $order->virtuemart_order_id . '&order_number=' . $order->order_number . '&order_pass=' . $order->order_pass . '&create_invoice=1';
					$invoice_link = '<a class="btn hasTooltip" title="' . JText::_ ('COM_VIRTUEMART_INVOICE_CREATE') . '" href="javascript:void window.open(\''.$invoice_url.'\', \'win2\', \'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no\');"  >';
					$invoice_link .= '<span class="icon icon-file-add"></span></a>';
				} elseif (!shopFunctions::InvoiceNumberReserved ($order->invoiceNumber)) {
					$invoice_url = juri::root () . 'index.php?option=com_virtuemart&view=invoice&layout=invoice&format=pdf&virtuemart_order_id=' . $order->virtuemart_order_id . '&order_number=' . $order->order_number . '&order_pass=' . $order->order_pass;
					$invoice_link = "<a class='btn hasTooltip' title='" . JText::_ ('COM_VIRTUEMART_INVOICE') . "' href=\"javascript:void window.open('$invoice_url', 'win2', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no');\"  >";
					$invoice_link .= '<span class="icon-file"></span></a>';
				}


				?>
				<td><?php echo $print_link; echo $invoice_link; ?></td>
				<!-- Order date -->
				<td>
					<span class="hidden-phone"><?php echo vmJsApi::date ($order->created_on, 'LC2', TRUE); ?></span>
					<span class="visible-phone"><?php echo vmJsApi::date ($order->created_on, 'LC4', TRUE); ?></span>
				</td>
				<!-- Last modified -->
				<td>
				
					<span class="hidden-phone"><?php echo vmJsApi::date ($order->modified_on, 'LC2', TRUE); ?></span>
					<span class="visible-phone"><?php echo vmJsApi::date ($order->modified_on, 'LC4', TRUE); ?></span>
				</td>
				<!-- Status -->
				<td class="status-change">
					<?php echo JHTML::_ ('select.genericlist', $this->orderstatuses, "orders[" . $order->virtuemart_order_id . "][order_status]", 'class="input-medium"', 'order_status_code', 'order_status_name', $order->order_status, 'order_status' . $i, TRUE); ?>
					<input type="hidden" name="orders[<?php echo $order->virtuemart_order_id; ?>][current_order_status]" value="<?php echo $order->order_status; ?>"/>
					<div class="hidden fade">
						<?php //echo JHTML::_ ('link', '#', JText::_ ('COM_VIRTUEMART_ADD_COMMENT'), array('class' => 'show_comment')); ?>
						<!-- Update -->
						<div class="row-fluid">
						<?php echo VmHTML::checkbox ('orders[' . $order->virtuemart_order_id . '][customer_notified]', 1) . JText::_ ('COM_VIRTUEMART_ORDER_LIST_NOTIFY'); ?>
						</div><div class="row-fluid">
						<?php echo VmHTML::checkbox ('orders[' . $order->virtuemart_order_id . '][update_lines]', 1) . JText::_ ('COM_VIRTUEMART_ORDER_UPDATE_LINESTATUS'); ?>
						</div>
						<textarea class="input-medium" name="orders[<?php echo $order->virtuemart_order_id; ?>][comments]" cols="5" rows="2"></textarea>
						<div class="row-fluid">
						<?php echo VmHTML::checkbox ('orders[' . $order->virtuemart_order_id . '][customer_send_comment]', 1) . JText::_ ('COM_VIRTUEMART_ORDER_HISTORY_INCLUDE_COMMENT'); ?>
						</div>
						
					</div>
				</td>
				<!-- Total -->
				<td><?php echo $order->order_total; ?></td>
				<td class="visible-desktop">
					<?php echo $order->virtuemart_order_id ?>
				</td>

			</tr>
				<?php
		
				$i++;
			}
		}
		?>
		</tbody>
		<tfoot>
		<tr>
			<td colspan="11">
				<?php echo $this->pagination->getListFooter (); ?>
			</td>
		</tr>
		</tfoot>
	</table>
	<!-- Hidden Fields -->
	<?php echo $this->addStandardHiddenToForm (); ?>
<script type="text/javascript">
	jQuery('td.status-change select').change(function() {
		jQuery(this).siblings('.fade').removeClass('hidden').addClass('in');
	});

</script>

