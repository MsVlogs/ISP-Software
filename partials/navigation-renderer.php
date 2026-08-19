<?php
/**
 * Render navigation-config.php using the existing permission API.
 * Language is selected by ?lang=bn or session language; English is default.
 */

if (!function_exists('isp_current_language')) {
    function isp_current_language(): string
    {
        $lang = $_GET['lang'] ?? ($_SESSION['isp_language'] ?? 'en');
        $lang = in_array($lang, ['en', 'bn'], true) ? $lang : 'en';
        $_SESSION['isp_language'] = $lang;
        return $lang;
    }
}

if (!function_exists('isp_menu_label')) {
    function isp_menu_label(array $item, string $lang): string
    {
        return $item['label'][$lang] ?? $item['label']['en'] ?? $item['key'];
    }
}

if (!function_exists('isp_can_menu')) {
    function isp_can_menu($obj, array $item): bool
    {
        if (array_key_exists('permission', $item)) {
            if ($item['permission'] === null) {
                return true;
            }
            return (bool) $obj->userMenuePermission($item['permission']);
        }

        foreach (($item['permission_any'] ?? []) as $permission) {
            if ($obj->userMenuePermission($permission)) {
                return true;
            }
        }
        return empty($item['permission_any']);
    }
}

if (!function_exists('isp_render_navigation')) {
    function isp_render_navigation($obj, array $items, string $lang): void
    {
        usort($items, static fn($a, $b) => ($a['order'] ?? 9999) <=> ($b['order'] ?? 9999));

        foreach ($items as $item) {
            if (!isp_can_menu($obj, $item)) {
                continue;
            }

            $children = array_values(array_filter(
                $item['children'] ?? [],
                static fn($child) => isp_can_menu($obj, $child)
            ));
            $hasChildren = count($children) > 0;
            $label = htmlspecialchars(isp_menu_label($item, $lang), ENT_QUOTES, 'UTF-8');
            $icon = htmlspecialchars($item['icon'] ?? 'mdi mdi-circle-outline', ENT_QUOTES, 'UTF-8');
            $route = $item['route'] ?? null;

            echo '<li' . ($hasChildren ? ' class="dropdown"' : '') . '>';
            echo '<a href="' . htmlspecialchars($route ?: 'javascript:void(0)', ENT_QUOTES, 'UTF-8') . '">';
            echo '<i class="' . $icon . ' menu-icon"></i><span>' . $label . '</span></a>';

            if ($hasChildren) {
                echo '<ul class="sidebar-submenu">';
                foreach ($children as $child) {
                    $childLabel = htmlspecialchars(isp_menu_label($child, $lang), ENT_QUOTES, 'UTF-8');
                    $childRoute = htmlspecialchars($child['route'] ?? '#', ENT_QUOTES, 'UTF-8');
                    echo '<li><a href="' . $childRoute . '">';
                    echo '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>' . $childLabel . '</a></li>';
                }
                echo '</ul>';
            }
            echo '</li>';
        }
    }
}
