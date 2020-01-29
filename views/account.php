<p id="ccf-balance-block">
    <strong><?php esc_html_e( 'Balance', 'content-copy-finder' ); ?>: <span id="ccf-balance"></span></strong><span class="spinner is-active" style="float:none; margin-top:-2px;"></span><br>
    <span class="description"><?php esc_html_e( 'Check price', 'content-copy-finder' ); ?> <span id="ccf-tarif">0.25</span> <?php esc_html_e( 'rub.', 'content-copy-finder' ); ?> <?php esc_html_e( 'At your disposal', 'content-copy-finder' ); ?> <span id="ccf-tarif-limit">?</span> <?php esc_html_e( 'checks', 'content-copy-finder' ); ?>.</span>
</p>
<p id="ccf-error-block">
    <strong></strong>
</p>
<p>
    <button data-nonce="<?php echo esc_attr( wp_create_nonce( 'ccf_balance' ) ); ?>" class="button" id="ccf-check-balance"><?php esc_html_e( 'Check balance', 'content-copy-finder' ); ?></button>
    <a class="button" href="http://content-watch.ru/pay/?ref=Pvumudx6v0prYpU#api" target="_blank"><?php esc_html_e( 'Add funds', 'content-copy-finder' ); ?></a>
</p>