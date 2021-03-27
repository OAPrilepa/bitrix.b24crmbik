$(function () {
    function __htmlspecialchars_decode(encodedStr) {
        return $("<div/>").html(encodedStr).text();
    }
    if (typeof BX !== "undefined") {
        $(document)
            .ajaxError(BX.closeWait)
            .ajaxStart(BX.showWait)
            .ajaxStop(BX.closeWait);
    }
    $(document).on(
        'change keyup',
        'input[name*=RQ_BIK]',
        function () {
            var $bik = $(this),
                $container = $bik.parents('.crm-multi-address-item:first,[data-cid="BANK_DETAILS"]:first'),
                $bank_name = $container.find('input[name*=RQ_BANK_NAME]'),
                $bank_ks = $container.find('input[name*=RQ_COR_ACC_NUM]'),
                $bank_address = $container.find('textarea[name*=RQ_BANK_ADDR]'),
                $bank_acc = $container.find('input[name*=RQ_ACC_NUM]');
            bikValue = $.trim($bik.val());

            $bik.removeAttr('title')
                .removeAttr('style');

            if (bikValue.length > 8) {
                $.get(
                    'https://bik-info.ru/api.html',
                    {
                        type: 'json',
                        bik: bikValue
                    },
                    function (answ) {
                        if (undefined != answ.error) {
                            $bik
                                .attr('title', answ.error)
                                .css({border: '1px solid red'})
                            ;
                        } else {
                            if (!$.trim($bank_name.val()).length) {
                                $bank_name.val(__htmlspecialchars_decode(answ.name));
                            }
                            if (!$.trim($bank_ks.val()).length) {
                                $bank_ks.val(answ.ks);
                            }
                            if (!$.trim($bank_address.val()).length) {
                                $bank_address.val(__htmlspecialchars_decode([
                                    answ.index,
                                    answ.city,
                                    answ.address
                                ].join(', ')));
                            }
                            $bank_acc.focus();
                        }
                    },
                    'json'
                );
            }
        }
    );
});