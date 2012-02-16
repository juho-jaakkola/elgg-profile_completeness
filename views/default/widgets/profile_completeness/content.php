<?php 
/**
 * Profile completeness widget
 *
 * @package ProfileCompleteness
 * @license GPL
 * @author Juho Jaakkola <juho.jaakkola@mediamaisteri.com>
 * @copyright (C) 2011, 2012 Mediamaisteri Group
 * @link http://www.mediamaisteri.com/
 */

$owner = elgg_get_page_owner_entity();
$user = elgg_get_logged_in_user_entity();

$own_profile = true;
if ($owner instanceof ElggUser && $owner != $user) {
	// Viewing someone else's profile
	$user = $owner;
	$own_profile = false;
}

//elgg_load_library('elgg:profile_completeness'); // @todo Is this call needed?
$helper = new ProfileCompletenessHelper($user);

if (!$helper->isProfileComplete()) {
	$completeness = $helper->getPercentage();
		
	// Decide bar color on the completeness of the profile
	if ($completeness < 33) {
		$color = "red";
	} elseif ($completeness < 66) {
		$color = "yellow";
	} else {
		$color = "green";
	}
	
	$missing = $helper->getMissingFields();
	$filled = $helper->getFilledFields();
?>

<div class="profile-completeness">
	<div class="progress-bar-background">
		<div class="progress-bar" style="width:<?php echo $completeness; ?>%; background: <?php echo $color; ?>;"></div>
	</div>
	<p><?php echo sprintf(elgg_echo('profile_completeness:percentage:description'), $completeness); ?></p>
	
	<?php // View profile completing hints only if viewing own profile ?>
	<?php if ($completeness < 100 && $own_profile): ?>
		<?php echo elgg_echo('profile_completeness:increase_percentage'); ?>
		<ul>
			<?php for ($field = 0; $field < $helper->getTipAmount(); $field++): ?>
				<li><?php echo sprintf(elgg_echo('profile_completeness:information:add'), elgg_echo('profile:' . $missing[$field])); ?></li>
			<?php endfor; ?>
		</ul>
		<br />
		
		<?php
		if ($helper->isImageMissing()) {
			echo elgg_view('output/url', array(
				'href' => "avatar/edit/$user->username/",
				'text' => elgg_echo('profile_completeness:add_image'),
			));
		}
		
		if (count($missing) > 0) {
			echo "<br />";
			echo elgg_view('output/url', array(
				'href' => "profile/$user->username/edit/",
				'text' => elgg_echo('profile_completeness:edit_profile'),
			));
		}
		?>
</div>
	<?php endif; ?>
<?php
}

