<div class="ticketWidget">
    <h3>Заявка</h3>
    <form id="ticketWidgetForm">
        @csrf

        <div class="field">
            <input type="text" name="name" id="name" placeholder="Имя">
            <p id="name_errors" class="error"></p>
        </div>

        <div class="field">
            <input type="tel" name="phone" id="phone" placeholder="Телефон">
            <p id="phone_errors" class="error"></p>
        </div>

        <div class="field">
            <input type="email" name="email" id="email" placeholder="Почта">
            <p id="email_errors" class="error"></p>
        </div>

        <div class="field">
            <input type="text" name="subject" id="subject" placeholder="Тема">
            <p id="subject_errors" class="error"></p>
        </div>

        <div class="field">
            <input type="text" name="text" id="text" placeholder="Текст">
            <p id="text_errors" class="error"></p>
        </div>

        <div class="field">
            <input type="file" name="files[]" id="files" multiple>
            <p id="files_errors" class="error"></p>
        </div>

        <input type="button" id="send-button" value="Отправить">
        <p id="message"></p>
    </form>

    <script>
        const url = "{{ route('tickets.store') }}";
        const ticketWidgetForm = document.getElementById("ticketWidgetForm");

        document.getElementById("send-button").onclick = function sendTicket() {
            hidde_error('email_errors');
            hidde_error('phone_errors');
            hidde_error('name_errors');
            hidde_error('subject_errors');
            hidde_error('text_errors');
            hidde_error('message');

            const formData = new FormData(ticketWidgetForm);

            const xhr = new XMLHttpRequest();
            xhr.open("POST", url, true);
            xhr.setRequestHeader("Accept", "application/json")
            xhr.send(formData);
            xhr.onload = () => {
                if (xhr.status === 201) {
                    let data = JSON.parse(xhr.responseText);
                    let element = document.getElementById('message');

                    element.textContent = 'Заявка успешно отправлена, id заявки - ' + data.data.id;
                    element.style.display = 'block';
                } else if (xhr.status === 422) {
                    let data = JSON.parse(xhr.responseText);

                    if (data.errors.email) {
                        display_error('email_errors', data.errors.email[0]);
                    }

                    if (data.errors.phone) {
                        display_error('phone_errors', data.errors.phone[0]);
                    }

                    if (data.errors.name) {
                        display_error('name_errors', data.errors.name[0]);
                    }

                    if (data.errors.subject) {
                        display_error('subject_errors', data.errors.subject[0]);
                    }

                    if (data.errors.text) {
                        display_error('text_errors', data.errors.text[0]);
                    }

                    if (data.errors.files) {
                        display_error('files_errors', data.errors.files[0]);
                    }

                    if (data.errors[0]){
                        display_error('message', data.errors[0]['title']);
                    }
                } else if (xhr.status === 413) {
                    display_error('files_errors', 'Файл слишком большой')
                } else {
                    display_error('message', 'Кажется что-то пошло не так');
                }
            };
        }

        function display_error(block, error) {
            let element = document.getElementById(block);
            element.textContent = 'Ошибка: ' + error;
            element.style.display = 'block';
        }

        function hidde_error(block){
            let element = document.getElementById(block);
            element.style.display = 'none';
        }
    </script>
</div>

<style>
    .ticketWidget {
        box-sizing: border-box;
        width: 320px;
        max-width: 100%;
        padding: 14px;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        background: #ffffff;
        box-shadow: 0 6px 18px rgba(20, 20, 20, 0.04);
        font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
        color: #111;
        font-size: 14px;
        line-height: 1.3;
    }

    .ticketWidget h3 {
        margin: 0 0 10px;
        font-size: 16px;
        font-weight: 600;
        color: #222;
    }

    .ticketWidget form {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .ticketWidget .field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .ticketWidget input[type="text"],
    .ticketWidget input[type="email"],
    .ticketWidget input[type="tel"],
    .ticketWidget input[type="file"],
    .ticketWidget textarea {
        width: 100%;
        box-sizing: border-box;
        padding: 9px 10px;
        border: 1px solid #d6d6d6;
        border-radius: 8px;
        background: #fafafa;
        font-size: 14px;
        outline: none;
        transition: border-color .15s ease, box-shadow .12s ease, background .12s ease;
    }

    .ticketWidget input:focus,
    .ticketWidget textarea:focus {
        border-color: #6aa2ff;
        box-shadow: 0 0 0 4px rgba(106,162,255,0.12);
        background: #fff;
    }

    .ticketWidget input[type="file"] {
        padding: 6px 8px;
        font-size: 13px;
    }

    .ticketWidget #send-button {
        display: inline-block;
        padding: 10px 12px;
        border: none;
        border-radius: 8px;
        background: #2563eb;
        color: #fff;
        font-weight: 600;
        cursor: pointer;
        transition: transform .06s ease, box-shadow .06s ease, opacity .12s ease;
        box-shadow: 0 6px 12px rgba(37,99,235,0.12);
    }

    .ticketWidget #send-button:hover { transform: translateY(-1px); }
    .ticketWidget #send-button:active { transform: translateY(0); }
    .ticketWidget #send-button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        box-shadow: none;
    }

    .ticketWidget p.error {
        margin: 0;
        color: #b91c1c;
        font-size: 13px;
        line-height: 1.2;
        display: none;
    }

    .ticketWidget p#message {
        margin: 0;
        font-size: 13px;
        padding: 8px;
        border-radius: 8px;
        display: none;
    }

    .ticketWidget p#message.error {
        display: block;
        background: #fff1f2;
        color: #991b1b;
        border: 1px solid rgba(153,27,27,0.06);
    }
</style>
