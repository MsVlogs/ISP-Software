<?php
/**
 * ISP-Software v2 navigation shell.
 * Menu structure is defined in navigation-config.php and rendered by the
 * reusable bilingual/permission-aware navigation renderer.
 */

$configPath = __DIR__ . '/navigation-config.php';
$rendererPath = __DIR__ . '/navigation-renderer.php';

if (!is_file($configPath) || !is_file($rendererPath)) {
    return;
}

$navigationItems = require $configPath;
require_once $rendererPath;
$lang = isp_current_language();
?>

<aside class="sidebar isp-sidebar-v2" id="sidebar">
    <button type="button" class="sidebar-close-btn" aria-label="Close navigation">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>

    <div class="sidebar-brand-area">
        <a href="?page=dashboard" class="sidebar-logo" aria-label="ISP-Software">
            <img src="assets/images/bsd/logo.png" alt="ISP-Software" class="light-logo">
            <img src="assets/images/bsd/logo.png" alt="ISP-Software" class="dark-logo">
            <img src="assets/images/bsd/logo.png" alt="ISP-Software" class="logo-icon">
        </a>
    </div>

    <div class="sidebar-menu-area">
        <div class="isp-sidebar-language" role="group" aria-label="Language">
            <a href="?lang=en" class="<?php echo $lang === 'en' ? 'active' : ''; ?>">English</a>
            <a href="?lang=bn" class="<?php echo $lang === 'bn' ? 'active' : ''; ?>">বাংলা</a>
        </div>
        <ul class="sidebar-menu isp-navigation-v2" id="sidebar-menu">
            <?php isp_render_navigation($obj, $navigationItems, $lang); ?>
        </ul>
    </div>
</aside>
