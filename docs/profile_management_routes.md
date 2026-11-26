## **Описание роутов управления профилем**
*Профиль - сущность, свзяанная с пользователем. Создается автоматически App\Observers\UserObserver при создании Юзера.*

### Роуты управления профилем

### 1. GET /profile 
#### - name('profile.show')
*Возвращает профиль авторизованного пользователя.*

*Входные данные*: Request  
*Формат ответа*: (200 OK) - text/html


### 2. PATCH /profile
#### - name('profile.update')
*Обновляет данные в профиле пользователя*
*Входные данные*: ProfileUpdateRequest $request

| Параметр | Тип | Обязательный | Правила валидации | Описание |
| -------- | ----|--------------|-------------------|----------|
|  'name'  | string | Да | required, string, max:255 | Имя пользователя |
| 'nickname' | string | Да | required, string, max:255, unique | Никнейм |
| 'email' |  string | Да | required, string, lowercase, max:255, unique | Почта пользователя |
| 'birthday' | date | Да | nullable, date | День рождения |

*Формат ответа*: (302 OK)


### 3. DELETE /profile
#### - name('profile.destroy')
*Удаляет профиль пользователя.*

*Входные данные*: Request  
*Формат ответа*: (302 OK) 

### 4. GET /profile/edit
#### - name('profile.edit')
*Возвращает форму для редактирования данных профиля.*

*Входные данные*: Request  
*Формат ответа*: (200 OK) - text/html