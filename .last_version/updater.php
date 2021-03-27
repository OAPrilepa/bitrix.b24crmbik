<?php
/**@global \CUpdater $updater */
if (IsModuleInstalled($updater->moduleID)) {
	$updater->CopyFiles(
		'install/js',
		'js/' . $updater->moduleID,
		true,
		true
	);
}