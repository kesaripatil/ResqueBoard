<?php
/**
 * job template
 *
 * Website jobs page
 *
 * PHP version 5
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author        Wan Qi Chen <kami@kamisama.me>
 * @copyright     Copyright 2012, Wan Qi Chen <kami@kamisama.me>
 * @link          http://resqueboard.kamisama.me
 * @package       resqueboard
 * @subpackage	  resqueboard.template
 * @since         1.5.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>

    <ul class="nav nav-tabs page-nav-tab">
	    <li>
	    	<a href="/jobs/view">Processed Jobs</a>
	    </li>
	    <li class="active">
	    	<a href="/jobs/pending">Pending Jobs</a>
	    </li>
	    <li>
	    	<a href="/jobs/scheduled" title="View all scheduled jobs">Scheduled Jobs</a>
	    </li>
    </ul>

	<div class="with-sidebar">
		<div class="bloc">

		<?php

		echo '<h2>Pending Jobs';

		if (isset($pagination->uri['queue'])) {
			echo ' from <mark>' .  $pagination->uri['queue'] . '</mark>';
		}

		if (!empty($pagination->totalResult)) {
			echo '<span class="badge pull-right">' . number_format($pagination->totalResult) . ' results</span>';
		}

		echo'</h2>';

		$message = 'No pending jobs found';

		if (isset($pagination->uri['queue']) && !empty($pagination->uri['queue'])) {
			$message .= ' for the queue <strong>' . $pagination->uri['queue'] . '</strong>';
		}

		if (!empty($jobs)) {

			?>
			<div class="breadcrumb clearfix">
				<div class="pull-right">
				<?php if (isset($pagination)) { ?>
					<form class="form-inline" data-event="ajax-pagination">
						<label>Display
						<select class="span1">
							<?php
							foreach ($resultLimits as $limit) {
								$params = array_merge($pagination->uri, array('limit' => $limit));
								echo '<option value="'.$pagination->baseUrl . http_build_query($params) . '"';
								if ($limit == $pagination->limit) {
									echo ' selected="selected"';
								}
								echo '>'.$limit.'</option>';
							}?>
						</select>
						</label>
					</form>
					<?php } ?>
					<div class="btn-group">
						<button class="btn" data-event="expand-all tooltip" title="Expand all"><i class="icon-folder-open"></i></button>
						<button class="btn" data-event="collapse-all tooltip" title="Collapse all"><i class="icon-folder-close"></i></button>
					</div>
				</div>
			<?php if (isset($pagination)) {
				echo 'Page ' . $pagination->current .' of ' . number_format($pagination->totalPage) . ', found ' . number_format($pagination->totalResult) . ' jobs';
				} ?>
			</div>

			<?php



			\ResqueBoard\Lib\JobHelper::renderJobs($jobs, $message);

			if (isset($pagination)) {
				?>
						<ul class="pager">
						<li class="previous<?php if ($pagination->current == 1) echo ' disabled'?>">
							<a href="<?php
								if ($pagination->current > 1) {
									echo $pagination->baseUrl . http_build_query(array_merge($pagination->uri, array('page' => $pagination->current - 1)));
								} else {
									echo '#';
								}
							?>">&larr; Older</a>
						</li>
						<li>
							Page <?php echo $pagination->current?> of <?php echo number_format($pagination->totalPage) ?>, found <?php echo number_format($pagination->totalResult) ?> jobs
						</li>
						<li class="next<?php if ($pagination->current == $pagination->totalPage) {
							echo ' disabled';
						}?>">
							<a href="<?php
								if ($pagination->current < $pagination->totalPage) {
									echo $pagination->baseUrl . http_build_query(array_merge($pagination->uri, array('page' => $pagination->current + 1)));
								} else {
									echo '#';
								}
							?>">Newer &rarr;</a>
						</li>
						</ul>

				<?php
			}


		} else {
			?>

				<div class="alert"><?php echo $message; ?></div>

			<?php
		}
		?>
		</div>
	</div>
</div>

	<?php
		$totalJobs = 0;
		foreach ($queues as $queueName => $stats) {
			if ($queueName === ResqueScheduler\ResqueScheduler::QUEUE_NAME) {
				continue;
			}
			$totalJobs += $stats['jobs'];
		}
	?>

	<div class="sidebar">
		<div class="page-header">
			<h3>Quick Stats <i class="icon-bar-chart"></i></h3>
		</div>

		<ul class="stats unstyled clearfix">
			<li class="<?php
				if (!isset($pagination->uri['queue']) || $pagination->uri['queue'] === '') {
					echo ' active';
				}
					 ?>"><a href="/jobs/pending">
				<strong><?php echo number_format($totalJobs); ?></strong>
				Total <b>pending</b> jobs</a>
			</li>
		</ul>

		<div class="bloc">
			<h3>Queues Stats</h3>
		</div>

		<ul class="stats unstyled clearfix">
			<?php foreach ($queues as $queueName => $stats) { ?>
				<li class="<?php if (empty($stats['workers'])) {
					echo 'error';
				}
				if (isset($pagination->uri['queue']) && $pagination->uri['queue'] === $queueName) {
					echo ' active';
				}
					 ?>">
					<a href="/jobs/pending?queue=<?php echo $queueName; ?>" title="View all pending jobs from <?php echo $queueName ?>">
						<strong>
							<?php echo number_format($stats['jobs']); ?>
						</strong> from <b><?php echo $queueName; ?></b>
					</a>
				</li>
			<?php } ?>
		</ul>


	</div>
</div>