<?php
require_once "config.php";
function __autoload($class) {
	require_once('lib/' . strtolower($class) . '.php');
}


function bootstrapItems($items, $html_root, $level = 0) {
	
	// Starting from items at root level
	if( !is_array($items) ) {
		$items = $items->roots();
	}
	
	foreach( $items as $item ) {
		$child = $item->hasChildren();
	?>
		<li<?php if($child && $level != 0): ?> class="dropdown-submenu"<?php endif ?>>
			<a href="<?php echo $html_root; ?><?php echo $item->link->get_url() ?>"<?php if($child): ?> class="dropdown-toggle" data-toggle="dropdown" <?php else: ?> target="frame2"<?php endif ?>>
		 	<?php echo $item->link->get_text() ?> <?php if($child && $level == 0): ?> <b class="caret"></b><?php endif ?></a>
			<?php if($child): ?>
				<ul class="dropdown-menu<?php echo ($level == 0) ? ' multi-level' : ''; ?>">
					<?php bootstrapItems( $item->children(), $html_root, $level + 1 ) ?>
				</ul> 
			<?php endif ?>
		</li>
	<?php
	}
}

?>