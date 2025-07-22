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
                var category = response.category;
                $('#edit_category_id').val(category.category_id);
                $('#edit_category_name').val(category.category_name);
                $('#edit_category_description').val(category.category_description);
                $('#edit_parent_category').val(category.parent_id);
                
                $('#categoryEditModal').addClass('active');
                $('body').addClass('no-scroll');
            },
            error: function() {
                console.error('Ошибка при загрузке категории');
            }
        });
    });

    // Открытие модалки удаления категории
    $(document).on('click', '.admin-action-btn[title="Удалить"]', function(e) {
        e.preventDefault();
        var categoryId = $(this).data('category-id');
        $('#delete_category_id').val(categoryId);

        $('#categoryDeleteModal').addClass('active');
        $('body').addClass('no-scroll');
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
                if (response.status === 'success') {
                    $('#categoryAddModal').removeClass('active');
                    $('body').removeClass('no-scroll');
                    setTimeout(function() {
                        $('#categoryAddModal').remove();
                        location.reload();  // Перезагрузка страницы после добавления
                    }, 300);
                } else {
                    alert('Ошибка: ' + response.message);
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
                if (response.status === 'success') {
                    $('#categoryEditModal').removeClass('active');
                    $('body').removeClass('no-scroll');
                    setTimeout(function() {
                        $('#categoryEditModal').remove();
                        location.reload();  // Перезагрузка страницы после редактирования
                    }, 300);
                } else {
                    alert('Ошибка: ' + response.message);
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
                if (response.status === 'success') {
                    $('#categoryDeleteModal').removeClass('active');
                    $('body').removeClass('no-scroll');
                    setTimeout(function() {
                        $('#categoryDeleteModal').remove();
                        location.reload();  // Перезагрузка страницы после удаления
                    }, 300);
                } else {
                    alert('Ошибка: ' + response.message);
                }
            },
            error: function() {
                alert('Ошибка при удалении категории');
            }
        });
    });
});
