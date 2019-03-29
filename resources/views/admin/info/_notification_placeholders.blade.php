<div class="br br-dashed br-grey br0 bw2 mt15 p10" style="background:#f8f8f8;">
    Ви можете використовувати наступні змінні:<br><br>
    <ul>
        <li><b>{user.name}</b> - Прізвище та Ім'я користувача (Іванов Іван)</li>
        <li><b>{user.full_name}</b> - Прізвище, Ім'я та По батькові користувача (Іванов Іван Іванович)</li>
        <li><b>{user.first_name}</b> - Ім'я користувача (Іван)</li>
        <li><b>{user.last_name}</b> - Прізвище користувача (Іванов)</li>
        <li><b>{user.middle_name}</b> - По батькові користувача (Іванович)</li>
        <li><b>{user.animals.count}</b> - Кількість всіх тварин користувача</li>
        <li><b>{user.animals_verified.count}</b> - Кількість верифікованих тварин користувача</li>
        <li><b>{user.animals_unverified.count}</b> - Кількість не верифікованих тварин користувача</li>
        @if(strrpos(Route::current()->getName(), 'template') === false)
        <li><b>{animal.nickname}</b> - Кличка тварини (опціонально)</li>
        <li><b>{animal.badge_num}</b> - Номер жетону тварини (опціонально)</li>
        @endif
    </ul>
    <br>
    Та наступні змінні для повідомлень про знайдену тварину:<br><br>
    <ul>
        <li><b>{found_animal.species}</b> - Вид тварини (опціонально)</li>
        <li><b>{found_animal.breed}</b> - Порода тварини (опціонально)</li>
        <li><b>{found_animal.color}</b> - Окрас тварини (опціонально)</li>
        <li><b>{found_animal.badge}</b> - Номер жетону (опціонально)</li>
        <li><b>{found_animal.found_address}</b> - Адреса та міце де знайдено тварину (опціонально)</li>
        <li><b>{found_animal.additional_info}</b> - Додаткова інформація (опціонально)</li>
        <li><b>{found_animal.contact_name}</b> - Ім'я</li>
        <li><b>{found_animal.contact_phone}</b> - Телефон</li>
        <li><b>{found_animal.contact_email}</b> - E-mail</li>
    </ul>
    @if(strrpos(Route::current()->getName(), 'template') === false)
    <br>
    (опціонально) - змінна яка відображається лише в тому випадку коли подія містить цю інформацію.
    @endif
</div>