<?php
// Simple helper view untuk debugging flashdata
?>
<?php if (!empty($message)) : ?>
<div style="white-space:pre-wrap; background:#fff3cd; border:1px solid #ffeeba; padding:10px; margin:10px 0;">
  <b>ERROR:</b>
  <div><?= esc($message) ?></div>
</div>
<?php endif; ?>

