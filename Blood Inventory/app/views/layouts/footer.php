<?php
declare(strict_types=1);
?>
</div>
<script>
(function () {
    function setBanner(moduleName, type, message) {
        const elId = "ajax-banner-" + moduleName;
        const el = document.getElementById(elId);
        if (!el) {
            if (type === 'err') {
                alert(message);
            }
            return;
        }
        el.style.display = 'block';
        el.className = 'flash ' + (type === 'ok' ? 'ok' : 'err');
        el.textContent = message || '';
    }

    async function refreshList(moduleName) {
        const base = "<?= e(rtrim((string) config('base_path', ''), '/')) ?>";
        const targetId = moduleName + "-list-container";
        const target = document.getElementById(targetId);
        if (!target) return;
        const params = new URLSearchParams(window.location.search);
        params.set("_ajax", "1");
        const res = await fetch(base + "/" + moduleName + "?" + params.toString(), {
            headers: {"X-Requested-With": "XMLHttpRequest"}
        });
        const json = await res.json();
        if (json && json.ok && json.html) {
            target.innerHTML = json.html;
        }
    }

    document.addEventListener("submit", async function (event) {
        const form = event.target;
        if (!(form instanceof HTMLFormElement)) return;
        const moduleName = form.getAttribute("data-ajax-form");
        if (!moduleName) return;
        event.preventDefault();
        const body = new FormData(form);
        body.append("_ajax", "1");
        const res = await fetch(form.action, {
            method: "POST",
            headers: {"X-Requested-With": "XMLHttpRequest"},
            body
        });
        let json = null;
        try {
            json = await res.json();
        } catch (e) {
            setBanner(moduleName, 'err', "Request failed. Please refresh and try again.");
            return;
        }
        if (!json || !json.ok) {
            setBanner(moduleName, 'err', (json && json.message) ? json.message : "Request failed.");
            return;
        }
        if (json && json.message) {
            setBanner(moduleName, 'ok', json.message);
        }
        form.reset();
        await refreshList(moduleName);
    });
})();
</script>
</body>
</html>

