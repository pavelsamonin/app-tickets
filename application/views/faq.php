<? if ( ! $this->input->is_pjax_request())
{ ?>
    <!DOCTYPE html>
    <html lang="<?= lang('var.lang') ?>">
    <? $this->load->view('base/head'); ?>
    <body>
    <div id="page-wrap">
    <? $this->load->view('base/header'); ?>
    <main class="content">
    <? $this->load->view('base/leftside'); ?>
<? } ?>
    <div class="content-wrapp">
        <div class="faq">
            <div class="faq_item">
                <div class="title"><?= lang('faq.moneysteam') ?></div>
                <div class="text"><?= lang('faq.moneysteam_') ?></div>
            </div>
            <div class="faq_item">
                <div class="title"><?= lang('faq.noaddbalance') ?></div>
                <div class="text"><?= lang('faq.noaddbalance_') ?></div>
            </div>
            <div class="faq_item">
                <div class="title"><?= lang('faq.problemsell') ?></div>
                <div class="text"><?= lang('faq.problemsell_') ?></div>
            </div>
            <div class="faq_item">
                <div class="title"><?= lang('faq.waitidrop') ?></div>
                <div class="text"><?= lang('faq.waitidrop_') ?></div>
            </div>
            <div class="faq_item">
                <div class="title"><?= lang('faq.beforeopen') ?></div>
                <div class="text"><?= lang('faq.beforeopen_') ?></div>
            </div>
            <div class="faq_item">
                <div class="title"><?= lang('faq.problemtrade') ?></div>
                <div class="text"><?= lang('faq.problemtrade_') ?></div>
            </div>

            <? if ($this->settings->SHARDS_ACTIVE == 1): ?>
                <div class="faq_item">
                    <div class="title"><?= lang('faq.shard') ?></div>
                    <div class="text"><?= lang('faq.shard_') ?></div>
                </div>
            <? endif; ?>
        </div>
    </div>
<? if ( ! $this->input->is_pjax_request())
{ ?>
    </main>
    <? $this->load->view('base/footer'); ?>
    </div>
    </body>
    </html>
<? } ?>