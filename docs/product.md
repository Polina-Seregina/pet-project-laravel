## **Продукт**

*Продукт/товар - цифровые арты.*
*Арты могут быть созданы зарегистрированными и авторизованными пользователями. Связь с моделью User один ко многим через поле user_id.*
*Редактирование, обновление и удаление арта доступно только его владельцу. Это условие обеспечивает App\Policies\ProductPolicy.* 

### Роуты управления продуктом

### 1. GET /products
#### - name('products.index')
*Возвращает страницу с отображением всех артов, доступных для приобретения.*

*Формат ответа*: (200 OK) - text/html

### 2. GET /products/create
#### - name('products.create')
*Возвращает форму для создания нового Арта.*
*Форма содержит следующие поля:*
*-Name - название арта*
*-Description - описание арта*
*-Price - cтоимость арта*
*-Image - изображение*

*Формат ответа*: (200 OK) - text/html

### 3. GET /my/products
#### - name('user.products.index')
*Возвращает страницу с отображением всех артов, принадлежащих пользователю.*

*Входные данные*: Request  
*Формат ответа*: (200 OK) - text/html

### 4. GET /products/{product}
#### - name('products.show')
*Возвращает страницу/профиль конкретного арта с более подробной информацией о нем - Цена, описание, атвор, крупное изображение.*
*Роут защищен мидлварой 'onlyPublic'. Просмотреть можно только страницу продукта в статусе 'for_sale'.*

*Входные данные*: Request, Product $product  
*Формат ответа*: (200 OK) - text/html

### 5. GET /products/{product}/edit
#### - name('products.edit')

*Возвращает форму для редактирования Арта. Содержит поля, аналогичные форме создания.*
*Поля автоматически заполняются старыми данными арта.*

*Входные данные*: Request, Product $product  
*Формат ответа*: (200 OK) - text/html

### 6. POST /products
#### - name('products.store')

*Создает новый товар. Автоматически присваивает статус - 'for sale'.*

*Входные данные*: ProductStoreRequest

| Параметр | Тип | Обязательный | Правила валидации | Описание |
| -------- | ----|--------------|-------------------|----------|
| 'name'  | string | Да | required, string, max:255| Название арта|
| 'description' | string | Да | required, max:255| Описание арта |
| 'price' |  decimal | Да | required, numeric, min:0.01 | Cтоимость арта |
| 'image' | string | да | required, image| Изображение |

*Формат ответа*: (302 OK)

### 7. PATCH /products/{product}
#### - name('products.update')

*Обновляет данные арта. Все нежеуказанные поля доступны для редактирования только Автору, владеющему артом. Пользователь-владелец (user_id) не может редактировать image.*

*Входные данные*: ProductUpdateRequest, Product $product

| Параметр | Тип | Обязательный | Правила валидации | Описание |
| -------- | ----|--------------|-------------------|----------|
| 'name'  | string | нет | required, string, max:255| Название арта|
| 'description' | string | нет | required, max:255| Описание арта |
| 'price' |  decimal | нет | required, numeric, min:0.01 | Cтоимость арта |
| 'image' | string | нет | nullable, image| Изображение |

*Формат ответа*: (302 OK)

### 8. DELETE /products/{product}
#### - name('products.destroy')

*Удаляет арт.*

*Входные данные*: Request, Product $product  
*Формат ответа*: (302 OK) 

### 9. POST /products/{product}/buy 
#### - name('products.buy')

*Роут для покупки арта. Используется сервис App\Services\BuyProductService.*
*Роут защищен мидлварой 'onlyPublic'.*
*После успешного приобретения Арта приходят уведомления:*
*- продавцу по email,*
*- в Slack в канал 'orders-pet-project'.*

*Входные данные*: Request $request, Product $product, BuyProductService $buyProductService  
*Формат ответа*: (302 OK)


