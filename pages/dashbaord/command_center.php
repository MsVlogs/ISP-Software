<?php
/** ISP-Software UI/UX v2 command-center dashboard. */
require_once __DIR__ . '/../../services/OltService.php';
$pdo = oltDb();
$olts = $pdo ? oltGetActiveDevices() : [];
$stats = [];
$alerts = [];
$totalOnu = 0;
$totalDownload = 0;
$totalUpload = 0;
if ($pdo) {
    try {
        $sql = "SELECT o.device_name,o.ip_address,o.snmp_port,
                COALESCE(u.onu_count,0) onu_count,
                COALESCE(u.download_bytes,0) download_bytes,
                COALESCE(u.upload_bytes,0) upload_bytes,
                u.last_updated
                FROM tbl_olt_devices o
                LEFT JOIN (
                    SELECT olt_ip,COUNT(*) onu_count,SUM(download_bytes) download_bytes,
                    SUM(upload_bytes) upload_bytes,MAX(last_updated) last_updated
                    FROM onu_status GROUP BY olt_ip
                ) u ON CONVERT(u.olt_ip USING utf8mb4) COLLATE utf8mb4_general_ci=
                     CONVERT(o.ip_address USING utf8mb4) COLLATE utf8mb4_general_ci
                WHERE o.status=1 ORDER BY o.device_name";
        $stats = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        foreach ($stats as $row) {
            $totalOnu += (int)$row['onu_count'];
            $totalDownload += (float)$row['download_bytes'];
            $totalUpload += (float)$row['upload_bytes'];
            if (!(int)$row['onu_count']) $alerts[] = ['danger','OLT has no ONU data',$row['device_name']];
            if ($row['last_updated'] && strtotime($row['last_updated'].' UTC') < time()-900) {
                $alerts[] = ['warning','ONU data is older than 15 minutes',$row['device_name']];
            }
        }
    } catch (Throwable $e) {
        error_log('Command center network query failed: '.$e->getMessage());
        $alerts[] = ['warning','Network metrics temporarily unavailable','Monitoring database'];
    }
}
$activeCustomers = (int)($obj->Total_Count('tbl_agent', "ag_status='1'") ?? 0);
$inactiveCustomers = (int)($obj->Total_Count('tbl_agent', "ag_status='0'") ?? 0);
function ccGb($bytes): string { return number_format((float)$bytes / 1073741824, 2); }
$lang = $_SESSION['isp_language'] ?? ($_GET['lang'] ?? 'en');
$lang = in_array($lang, ['en','bn'], true) ? $lang : 'en';
$t = static function(string $en, string $bn) use ($lang): string { return $lang === 'bn' ? $bn : $en; };
?>
<div class="dashboard-v2 container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div><h4 class="mb-1"><?= $t('Network Command Center','নেটওয়ার্ক কমান্ড সেন্টার') ?></h4><div class="text-secondary"><?= $t('Live business and infrastructure overview','লাইভ ব্যবসা ও নেটওয়ার্ক অবস্থা') ?></div></div>
        <a class="btn btn-sm btn-outline-primary" href="?page=network_monitoring&view=map"><?= $t('Open Network Map','নেটওয়ার্ক ম্যাপ খুলুন') ?></a>
    </div>
    <div class="row g-3 mb-3">
        <?php $kpis=[
            [$t('Active Customers','সক্রিয় গ্রাহক'),$activeCustomers,'mdi mdi-account-group-outline'],
            [$t('Inactive Customers','নিষ্ক্রিয় গ্রাহক'),$inactiveCustomers,'mdi mdi-account-off-outline'],
            [$t('OLT Devices','OLT ডিভাইস'),count($olts),'mdi mdi-router-wireless'],
            [$t('Total ONUs','মোট ONU'),$totalOnu,'mdi mdi-access-point-network'],
            [$t('Download','ডাউনলোড'),ccGb($totalDownload).' GB','mdi mdi-download-network'],
            [$t('Upload','আপলোড'),ccGb($totalUpload).' GB','mdi mdi-upload-network'],
        ]; foreach($kpis as $k): ?>
        <div class="col-xxl-2 col-xl-4 col-md-6"><div class="isp-kpi"><div class="d-flex justify-content-between align-items-start"><div><div class="isp-kpi-label"><?=htmlspecialchars($k[0])?></div><div class="isp-kpi-value"><?=htmlspecialchars((string)$k[1])?></div></div><div class="isp-kpi-icon"><i class="<?=$k[2]?>"></i></div></div></div></div>
        <?php endforeach; ?>
    </div>
    <div class="row g-3">
        <div class="col-xl-8"><div class="isp-panel"><div class="isp-panel-header"><h6 class="isp-panel-title"><?= $t('OLT Health','OLT স্বাস্থ্য') ?></h6><span class="text-secondary small"><?=count($olts)?> <?= $t('active','সক্রিয়') ?></span></div><div class="isp-panel-body">
            <?php if(!$stats): ?><div class="text-secondary"><?= $t('No active OLT devices found.','কোনো সক্রিয় OLT পাওয়া যায়নি।') ?></div><?php else: foreach($stats as $s): $ok=(int)$s['onu_count']>0 && (!$s['last_updated'] || strtotime($s['last_updated'].' UTC')>=time()-900); ?>
            <div class="isp-health"><div><div class="isp-health-name"><?=htmlspecialchars($s['device_name'])?></div><div class="text-secondary small"><?=htmlspecialchars($s['ip_address'])?>:<?=intval($s['snmp_port'])?> · <?=intval($s['onu_count'])?> ONUs</div></div><span class="isp-status <?=$ok?'online':'down'?>">● <?=$ok?$t('ONLINE','অনলাইন'):$t('CHECK','পরীক্ষা করুন')?></span></div>
            <?php endforeach; endif; ?>
        </div></div></div>
        <div class="col-xl-4"><div class="isp-panel"><div class="isp-panel-header"><h6 class="isp-panel-title"><?= $t('Alerts','সতর্কতা') ?></h6><span class="badge bg-danger-subtle text-danger"><?=count($alerts)?></span></div><div class="isp-panel-body">
            <?php if(!$alerts): ?><div class="isp-status online">● <?=$t('All monitored OLTs look healthy','মনিটর করা সব OLT স্বাভাবিক')?></div><?php else: foreach(array_slice($alerts,0,8) as $a): ?><div class="mb-3"><span class="isp-status <?=$a[0]==='danger'?'down':'warning'?>"><?=$a[0]==='danger'?'●':'▲'?> <?=htmlspecialchars($a[1])?></span><div class="small text-secondary mt-1"><?=htmlspecialchars($a[2])?></div></div><?php endforeach; endif; ?>
        </div></div></div>
        <div class="col-xl-7"><div class="isp-panel"><div class="isp-panel-header"><h6 class="isp-panel-title"><?= $t('Network Overview','নেটওয়ার্ক ওভারভিউ') ?></h6></div><div class="isp-panel-body"><a class="isp-quick-action" href="?page=network_monitoring&view=map"><i class="mdi mdi-map-marker-radius text-primary fs-4"></i><div><strong><?=$t('Open Network Map','নেটওয়ার্ক ম্যাপ খুলুন')?></strong><div class="small text-secondary"><?=$t('Inspect live OLT → interface → ONU relationships','লাইভ OLT → interface → ONU সম্পর্ক দেখুন')?></div></div></a></div></div></div>
        <div class="col-xl-5"><div class="isp-panel"><div class="isp-panel-header"><h6 class="isp-panel-title"><?= $t('Quick Actions','দ্রুত কাজ') ?></h6></div><div class="isp-panel-body"><div class="row g-2"><div class="col-6"><a class="isp-quick-action" href="?page=olt_management"><i class="mdi mdi-router-wireless"></i><?=$t('OLT Management','OLT ব্যবস্থাপনা')?></a></div><div class="col-6"><a class="isp-quick-action" href="?page=device_condition"><i class="mdi mdi-heart-pulse"></i><?=$t('Device Condition','ডিভাইস কন্ডিশন')?></a></div><div class="col-6"><a class="isp-quick-action" href="?page=network_monitoring&view=traffic"><i class="mdi mdi-chart-line"></i><?=$t('Traffic','ট্রাফিক')?></a></div><div class="col-6"><a class="isp-quick-action" href="?page=network_monitoring&view=alerts"><i class="mdi mdi-bell-alert-outline"></i><?=$t('Alerts','সতর্কতা')?></a></div></div></div></div></div>
    </div>
</div>
