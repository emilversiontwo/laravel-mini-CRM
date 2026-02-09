<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <title>Edit Ticket</title>
</head>
<body>
<div class="card">
    <h1>Изменить статус тикета</h1>

    <div>
        <label>Ticket ID
            <div id="ticketIdText" class="small" style="margin-top:6px"></div>
        </label>
    </div>

    <label for="status">Status</label>
    <select id="status">
        <option value="new">new</option>
        <option value="at_work">at_work</option>
        <option value="processed">processed</option>
    </select>

    <div class="row">
        <button id="saveBtn" class="btn">Сохранить</button>
        <button id="backBtn" class="btn ghost" type="button">Назад</button>
    </div>

    <div id="resultOk" class="msg ok" style="display:none"></div>
    <div id="resultErr" class="msg err" style="display:none"></div>
</div>

<script>
    window.TICKETS_CONFIG = @json(['submitUrl' => route('tickets.update', ['ticket' => $ticket->id]), 'ticket'    => $ticket]);
</script>

<script>
    (function () {
        const $ = id => document.getElementById(id);
        const cfg = window.TICKETS_CONFIG || {};
        const submitUrl = cfg.submitUrl;
        const ticket = cfg.ticket || null;
        const ticketId = ticket ? String(ticket.id ?? '') : '';

        function getCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }

        const statusEl = $('status');
        const saveBtn = $('saveBtn');
        const backBtn = $('backBtn');
        const ticketIdText = $('ticketIdText');
        const resultOk = $('resultOk');
        const resultErr = $('resultErr');

        ticketIdText.textContent = ticketId || '-';
        if (ticket) {
            if (ticket.status) {
                try {
                    statusEl.value = ticket.status;
                } catch (e) {
                }
            }
        }

        function showOk(msg) {
            resultErr.style.display = 'none';
            resultOk.textContent = msg;
            resultOk.style.display = 'block';
        }

        function showErr(msg) {
            resultOk.style.display = 'none';
            resultErr.textContent = msg;
            resultErr.style.display = 'block';
        }

        function clearMsgs() {
            resultOk.style.display = 'none';
            resultErr.style.display = 'none';
        }

        function buildHeaders() {
            const headers = {'Accept': 'application/json'};
            const token = getCookie('api_token');
            if (token) {
                headers['Authorization'] = token.includes(' ') ? token : ('Bearer ' + token);
            }
            return headers;
        }

        saveBtn.addEventListener('click', async () => {
            clearMsgs();
            if (!submitUrl) {
                showErr('submitUrl не передан в шаблон.');
                return;
            }

            saveBtn.disabled = true;
            saveBtn.textContent = 'Отправка...';

            try {
                const form = new FormData();
                form.append('status', statusEl.value);

                const url = submitUrl;
                const headers = buildHeaders();

                const resp = await fetch(url, {method: 'PUT', headers, body: form});
                const text = await resp.text();
                let json = null;
                try {
                    json = JSON.parse(text);
                } catch (e) {
                }

                if (!resp.ok) {
                    const errMsg = (json && (json.message || json.error)) ? (json.message || json.error) : (text || `HTTP ${resp.status}`);
                    showErr('Ошибка: ' + errMsg);
                    console.error('PUT error', resp.status, text);
                } else {
                    showOk('Статус успешно обновлён.');
                    console.log('PUT result', json ?? text);
                }
            } catch (err) {
                console.error(err);
                showErr('Ошибка: ' + err.message);
            } finally {
                saveBtn.disabled = false;
                saveBtn.textContent = 'Сохранить';
            }
        });

        backBtn.addEventListener('click', () => history.back());

        window.ticketEditMin = {cfg, buildHeaders, getCookie};
    })();
</script>
</body>
</html>
