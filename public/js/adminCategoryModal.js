$(document).ready(function() {
    // Открытие модалки добавления категории
    $(document).on('click', '#addCategoryBtn', function() {
        $('#categoryAddModal').addClass('active');
        $('body').addClass('no-scroll');
    });

    // Открытие модалки редактирования категории
    $(document).on('click', '.admin-action-btn[title="Редактировать"]', function(e) {
        e.preventDefault();
        var categoryId = $(this).data('category-id');

        $.ajax({
            url: '../database/admin-category-edit.php',
            type: 'GET',
            data: { category_id: categoryId },
            success: function(response) {
                try {
                    // Проверка на корректный JSON ответ
                    if (typeof response !== "object") {
                        response = JSON.parse(response); // Попытка распарсить, если не объект
                    }

                    if (response.status === 'success') {
                        var category = response.category;
                        $('#edit_category_id').val(category.category_id);
                        $('#edit_category_name').val(category.category_name);
                        $('#edit_category_description').val(category.category_description);
                        
                        // Если родительская категория null, то установим "Без родителя"
                        $('#edit_parent_category').val(category.parent_id === null ? "" : category.parent_id);

                        // Заполняем список родительских категорий
                        var parentCategorySelect = $('#edit_parent_category');
                        parentCategorySelect.empty(); // Очищаем существующие элементы
                        parentCategorySelect.append('<option value="">Без родителя</option>');
                        response.categories.forEach(function(parentCategory) {
                            parentCategorySelect.append(
                                '<option value="' + parentCategory.category_id + '">' + parentCategory.category_name + '</option>'
                            );
                        });

                        // Показываем модалку
                        $('#categoryEditModal').addClass('active');
                        $('body').addClass('no-scroll');
                    } else {
                        console.error('Ошибка: ', response.message);
                    }
                } catch (e) {
                    console.error('Ошибка при парсинге JSON: ', e);
                    alert('Ошибка при загрузке данных категории');
                }
            },
            error: function() {
                alert('Ошибка при загрузке категории');
            }
        });
    });

    // Закрытие модалок
    $(document).on('click', '.admin-modal-close, .close-popup', function() {
        $('.admin-modal-overlay').removeClass('active');
        $('body').removeClass('no-scroll');
    });

    // Обработка добавления категории
    $('#addCategoryForm').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '../database/admin-category-add.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                try {
                    if (typeof response !== "object") {
                        response = JSON.parse(response); // Попытка распарсить, если не объект
                    }

                    if (response.status === 'success') {
                        location.reload();
                    } else {
                        console.error('Ошибка: ', response.message);
                    }
                } catch (e) {
                    console.error('Ошибка при парсинге JSON: ', e);
                    alert('Ошибка при добавлении категории');
                }
            },
            error: function() {
                alert('Ошибка при добавлении категории');
            }
        });
    });

    // Обработка редактирования категории
    $('#editCategoryForm').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '../database/admin-category-edit.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                try {
                    if (typeof response !== "object") {
                        response = JSON.parse(response); // Попытка распарсить, если не объект
                    }

                    if (response.status === 'success') {
                        $('#categoryEditModal').removeClass('active');
                        $('body').removeClass('no-scroll');
                        setTimeout(function() {
                            $('#categoryEditModal').remove();
                            location.reload();
                        }, 300);
                    } else {
                        console.error('Ошибка: ', response.message);
                    }
                } catch (e) {
                    console.error('Ошибка при парсинге JSON: ', e);
                    alert('Ошибка при сохранении изменений');
                }
            },
            error: function() {
                alert('Ошибка при сохранении изменений');
            }
        });
    });

    // Обработка удаления категории
    $('#deleteCategoryForm').submit(function(e) {
        e.preventDefault();

        $.ajax({
            url: '../database/admin-category-delete.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                try {
                    if (typeof response !== "object") {
                        response = JSON.parse(response); // Попытка распарсить, если не объект
                    }

                    if (response.status === 'success') {
                        location.reload();
                    } else {
                        console.error('Ошибка: ', response.message);
                    }
                } catch (e) {
                    console.error('Ошибка при парсинге JSON: ', e);
                    alert('Ошибка при удалении категории');
                }
            },
            error: function() {
                alert('Ошибка при удалении категории');
            }
        });
    });
});
