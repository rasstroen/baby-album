<?php require_once 'includes/template_helpers.php' ?>
<!DOCTYPE html>
<?php require_once 'includes/head.php'; ?>
<body class="l-body" id="body">
    <div class="l-container">
        <div class="l-header">
            <?php th_process_block('header'); ?>
        </div>
        <div class="l-wrapper">
            <div class="l-content wide">
                <?php th_process_block('content'); ?>
            </div>
        </div>
    </div>
    <div class="l-footer">
        <?php th_process_block('footer'); ?>
        <?php require_once 'includes/foot.php'; ?>
    </div>

</body>
