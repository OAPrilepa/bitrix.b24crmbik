<?php
namespace Prilepa;
class B24_CrmBik
{
	public function addJs()
	{
		if (strpos($_SERVER['REQUEST_URI'], '/bitrix/components/bitrix/crm.requisite.edit/slider.ajax.php') !== FALSE
            || strpos($_SERVER['REQUEST_URI'], '/bitrix/components/bitrix/crm.requisite.details/slider.ajax.php') !== FALSE
        ) {
			\CJSCore::init(['jquery']);
			$path = explode('/', __DIR__);
			\Bitrix\Main\Page\Asset::getInstance()->addJs(BX_ROOT . '/js/' . end($path) . '/main.js');
		}
	}
	public function addJsOldCards()
	{
		if (strpos($_SERVER['REQUEST_URI'], '/bitrix/components/bitrix/crm.requisite.edit/popup.ajax.php') !== FALSE) {
			$path = explode('/', __DIR__);
			?><script src="<?=\CUtil::GetAdditionalFileURL(BX_ROOT . '/js/main/jquery/jquery-2.1.3.js')?>"></script><?
			?><script src="<?=\CUtil::GetAdditionalFileURL(BX_ROOT . '/js/' . end($path) . '/main.js')?>"></script><?
		}
	}

}