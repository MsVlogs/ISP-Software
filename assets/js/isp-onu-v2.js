/* ISP-Software ONU/Device Condition v2 enhancement layer.
 * Presentation-only helpers; existing collector/API/data pipeline remains authoritative.
 */
(function () {
  'use strict';

  function classifyPower(value) {
    var n = Number(value);
    if (!Number.isFinite(n)) return 'unknown';
    if (n >= -25) return 'healthy';
    if (n >= -27) return 'warning';
    return 'critical';
  }

  function applyPowerClasses(root) {
    (root || document).querySelectorAll('[data-rx-power], [data-tx-power]').forEach(function (el) {
      var value = el.getAttribute('data-rx-power') || el.getAttribute('data-tx-power');
      el.classList.remove('isp-power-healthy', 'isp-power-warning', 'isp-power-critical', 'isp-power-unknown');
      el.classList.add('isp-power-' + classifyPower(value));
    });
  }

  function refreshRelativeTimestamps(root) {
    (root || document).querySelectorAll('[data-monitoring-time]').forEach(function (el) {
      var raw = el.getAttribute('data-monitoring-time');
      var time = Date.parse(raw);
      if (!Number.isFinite(time)) return;
      var seconds = Math.max(0, Math.floor((Date.now() - time) / 1000));
      var text = seconds < 60 ? seconds + 's ago' : Math.floor(seconds / 60) + 'm ago';
      el.setAttribute('title', raw);
      el.textContent = text;
    });
  }

  window.ISPOnuV2 = {
    classifyPower: classifyPower,
    refresh: function () {
      applyPowerClasses(document);
      refreshRelativeTimestamps(document);
    }
  };

  document.addEventListener('DOMContentLoaded', function () {
    window.ISPOnuV2.refresh();
  });
})();
