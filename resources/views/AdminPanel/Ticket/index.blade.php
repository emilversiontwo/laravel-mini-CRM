<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <title>Tickets</title>

</head>
<body>
<div class="wrap">
    <h1>Tickets</h1>

    <a href="{{ route('admin.dashboard') }}">Назад</a>

    <form id="filterForm" class="controls" onsubmit="return false;">
        <label>From
            <input id="from" type="datetime-local"/>
        </label>
        <label>To
            <input id="to" type="datetime-local"/>
        </label>
        <label>Subject
            <input id="subject" type="text" placeholder="subject"/>
        </label>
        <label>Text
            <input id="text" type="text" placeholder="text"/>
        </label>
        <label>Status
            <select id="status">
                <option value="">---</option>
                <option value="new">new</option>
                <option value="at_work">at_work</option>
                <option value="processed">processed</option>
            </select>
        </label>

        <label>Name
            <input id="name" type="text" placeholder="John Doe"/>
        </label>
        <label>Email
            <input id="email" type="email" placeholder="john@..."/>
        </label>
        <label>Phone
            <input id="phone" type="text" placeholder="+7 912 345 67 89"/>
        </label>

        <label>Per page
            <input id="per_page" type="number" min="1" max="200" value="10"/>
        </label>

        <div class="toolbar" style="margin-left:auto">
            <button class="btn" id="applyBtn">Apply</button>
            <button class="btn ghost" id="clearBtn" type="button">Clear</button>
        </div>
    </form>

    <div id="meta" class="small">
        <span id="infoText">Список пуст</span>
    </div>

    <div id="content">
        <div id="loading" class="loading" style="display:none">Загрузка…</div>

        <table id="ticketsTable" style="display:none">
            <thead>
            <tr>
                <th>ID</th>
                <th>Subject / Text</th>
                <th>Status</th>
                <th>Manager responded</th>
                <th>Customer</th>
                <th>Created / Updated</th>
                <th>Files</th>
            </tr>
            </thead>
            <tbody id="tbody"></tbody>
        </table>


        <div id="noData" class="small" style="display:none">Тикеты не найдены.</div>

        <div class="pager" style="justify-content:space-between">
            <div>
                <button id="prevPage" class="btn ghost">Prev</button>
                <button id="nextPage" class="btn ghost">Next</button>
            </div>
            <div class="small">Page <span id="curPage">1</span></div>
        </div>
    </div>
</div>

<script>
    (function () {
        const $ = id => document.getElementById(id);

        let page = 1;
        const perPageInput = $('per_page');

        function maybeISOZFromLocal(value) {
            if (!value) return null;
            try {
                const dt = new Date(value);
                return dt.toISOString();
            } catch (e) {
                return value;
            }
        }

        function buildQuery(params) {
            const esc = encodeURIComponent;
            const parts = [];
            for (const k in params) {
                const v = params[k];
                if (v === undefined || v === null || v === '') continue;
                parts.push(esc(k) + '=' + esc(v));
            }
            return parts.length ? '?' + parts.join('&') : '';
        }

        function renderTickets(list) {
            const tbody = $('tbody');
            tbody.innerHTML = '';
            if (!list || list.length === 0) {
                $('ticketsTable').style.display = 'none';
                $('noData').style.display = 'block';
                $('infoText').textContent = '0 тикетов';
                return;
            }
            $('ticketsTable').style.display = '';
            $('noData').style.display = 'none';
            $('infoText').textContent = `${list.length} тикетов на странице ${page}`;

            for (const t of list) {
                const tr = document.createElement('tr');

                const tdId = document.createElement('td');
                tdId.textContent = t.id ?? '-';
                tdId.className = 'mono';
                tr.appendChild(tdId);

                const tdSub = document.createElement('td');
                const subj = document.createElement('div');
                subj.textContent = t.subject ?? '(no subject)';
                subj.style.fontWeight = '600';
                const txt = document.createElement('div');
                txt.textContent = t.text ?? '';
                txt.className = 'small';
                tdSub.appendChild(subj);
                tdSub.appendChild(txt);
                tr.appendChild(tdSub);

                const tdStatus = document.createElement('td');
                const pill = document.createElement('span');
                pill.className = 'status-pill';
                pill.textContent = t.status ?? '';
                tdStatus.appendChild(pill);
                tr.appendChild(tdStatus);

                const tdMgr = document.createElement('td');
                tdMgr.textContent = t.manager_responded ?? '-';
                tr.appendChild(tdMgr);

                const tdCust = document.createElement('td');
                if (t.customer) {
                    const n = document.createElement('div');
                    n.textContent = t.customer.name ?? '-';
                    const e = document.createElement('div');
                    e.textContent = t.customer.email ?? '';
                    e.className = 'small';
                    const p = document.createElement('div');
                    p.textContent = t.customer.phone ?? '';
                    p.className = 'small';
                    tdCust.appendChild(n);
                    tdCust.appendChild(e);
                    tdCust.appendChild(p);
                } else {
                    tdCust.textContent = '-';
                }
                tr.appendChild(tdCust);

                const tdTimes = document.createElement('td');
                const created = t.created_at ?? t.crated_at ?? '-';
                const updated = t.updated_at ?? '-';
                const cdiv = document.createElement('div');
                cdiv.textContent = created;
                cdiv.className = 'small';
                const udiv = document.createElement('div');
                udiv.textContent = updated;
                udiv.className = 'small';
                tdTimes.appendChild(cdiv);
                tdTimes.appendChild(udiv);
                tr.appendChild(tdTimes);

                const tdFiles = document.createElement('td');
                if (Array.isArray(t.files) && t.files.length) {
                    const ul = document.createElement('ol');
                    ul.className = 'files-list';
                    for (const f of t.files) {
                        const li = document.createElement('li');
                        if (typeof f === 'string') li.textContent = f;
                        else if (f.name) li.textContent = f.name;
                        else li.textContent = JSON.stringify(f);
                        ul.appendChild(li);
                    }
                    tdFiles.appendChild(ul);
                } else tdFiles.textContent = '-';
                tr.appendChild(tdFiles);

                tr.style.cursor = 'pointer';
                tr.addEventListener('click', (ev) => {
                    const next = tr.nextSibling;
                    if (next && next.classList && next.classList.contains('detail-row')) {
                        next.remove();
                        return;
                    }
                    const existing = document.querySelectorAll('.detail-row');
                    existing.forEach(n => n.remove());

                    const detail = document.createElement('tr');
                    detail.className = 'detail-row';
                    const td = document.createElement('td');
                    td.colSpan = 7;
                    const wrapper = document.createElement('div');
                    wrapper.className = 'details';
                    var fileLinks = '';
                    var iterator = 1;
                    t.files.forEach(value => {
                        fileLinks = fileLinks + '<a href="' + value + '">Открыть файл '+ iterator + '</a><br>';
                        iterator = iterator + 1;
                    });
                    wrapper.innerHTML = '<a href="{{ route('welcome') }}/admin/tickets/' + t.id + '">Изменить статус</a><br>' + fileLinks;
                    td.appendChild(wrapper);
                    detail.appendChild(td);
                    tr.parentNode.insertBefore(detail, tr.nextSibling);
                });

                tbody.appendChild(tr);
            }
        }

        function escapeHtml(str) {
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }

        function setCookie(name,value,days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days*24*60*60*1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "")  + expires + "; path=/";
        }

        async function loadTickets() {
            const apiBase = "{{ route('tickets.index') }}";
            const params = {};
            const fromVal = maybeISOZFromLocal($('from').value);
            const toVal = maybeISOZFromLocal($('to').value);
            if (fromVal) params.from = fromVal;
            if (toVal) params.to = toVal;

            ['subject', 'text', 'status', 'name', 'email', 'phone'].forEach(k => {
                const v = $(k).value.trim();
                if (v) params[k] = v;
            });

            params.page = page;
            params.per_page = perPageInput.value || 10;

            const url = apiBase + buildQuery(params);
            $('loading').style.display = '';
            $('ticketsTable').style.display = 'none';
            $('noData').style.display = 'none';
            $('infoText').textContent = 'Загружаем...';

            try {
                const headers = {'accept': 'application/json'};
                const csrf = "{{ csrf_token() }}";
                if (csrf) headers['X-CSRF-TOKEN'] = csrf;

                const authVal = "{{ $token }}";
                if (authVal) {
                    headers['Authorization'] = authVal.includes(' ') ? authVal : ('Bearer ' + authVal);
                    setCookie('api_token', authVal, 7);
                }

                const resp = await fetch(url, {method: 'GET', headers});
                if (!resp.ok) {
                    const txt = await resp.text().catch(() => resp.statusText);
                    throw new Error(`HTTP ${resp.status}: ${txt}`);
                }
                const json = await resp.json();
                const list = Array.isArray(json) ? json : (Array.isArray(json.data) ? json.data : []);
                renderTickets(list);
            } catch (err) {
                console.error(err);
                $('ticketsTable').style.display = 'none';
                $('noData').style.display = 'block';
                $('infoText').textContent = 'Ошибка: ' + err.message;
            } finally {
                $('loading').style.display = 'none';
                $('curPage').textContent = page;
            }
        }

        // Controls
        $('applyBtn').addEventListener('click', () => {
            page = 1;
            loadTickets();
        });
        $('clearBtn').addEventListener('click', () => {
            const ids = ['from', 'to', 'subject', 'text', 'status', 'name', 'email', 'phone', 'csrf', 'auth'];
            ids.forEach(i => $(i).value = '');
            perPageInput.value = 10;
            page = 1;
            $('infoText').textContent = 'Сброшено';
            $('tbody').innerHTML = '';
            $('ticketsTable').style.display = 'none';
            $('noData').style.display = 'none';
        });

        $('prevPage').addEventListener('click', () => {
            if (page > 1) {
                page--;
                loadTickets();
            }
        });
        $('nextPage').addEventListener('click', () => {
            page++;
            loadTickets();
        });

        window.adminTickets = {loadTickets};
    })();
</script>
</body>
</html>
