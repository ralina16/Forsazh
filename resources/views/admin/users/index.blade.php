@extends('layouts.admin')
@section('title', 'Управление пользователями')

@push('styles')
<style>
    .badge-admin { background-color: #dc3545 !important; }
    .badge-user { background-color: #0d6efd !important; }
    .avatar {
        width: 35px; height: 35px;
        background: #007bff; color: white;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: bold; font-size: 0.9rem;
    }
    .dropdown-menu-custom {
        border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border-radius: 12px; padding: 8px;
        z-index: 10000 !important;
    }
    .dropdown-item-custom {
        border-radius: 8px; padding: 10px 15px; margin: 2px 0;
        display: flex; align-items: center; gap: 10px;
        transition: all 0.2s ease; font-size: 15px;
    }
    .dropdown-item-custom:hover {
        background: transparent !important; color: #4071CB;
        transform: translateX(5px);
    }
    .dropdown-divider-custom { margin: 8px 0; border-color: #e9ecef; }
    .password-toggle {
        position: absolute; right: 10px; top: 50%;
        transform: translateY(-50%);
        background: none; border: none;
        color: #6c757d; cursor: pointer; z-index: 5;
    }
    .password-input-wrapper { position: relative; }
    .form-control-with-icon { padding-right: 45px; }
    .modal-header-custom { background: transparent; color: #333; border-radius: 12px 12px 0 0; }
    .role-option {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 15px; border-radius: 8px;
        cursor: pointer; transition: all 0.2s ease;
    }
    .role-option:hover { background: #f8f9fa; }
    .role-option input[type="radio"] { margin: 0; }
    .dropdown-item-custom i { margin-right: 8px; width: 16px; text-align: center; }
    .role-icon { width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; }
    .validation-message {
        display: none; color: #dc3545; font-size: 0.875em; margin-top: 0.25rem;
    }
    .form-general-error { display: none; }
</style>
@endpush

@section('content')
<div class="container-xxl section">
    <div class="d-none d-xl-block mt-2 mb-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Главная</a></li>
                <li class="breadcrumb-item active" aria-current="page">Пользователи</li>
            </ol>
        </nav>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-5 w-100">
        <h4 class="mb-0">Пользователи</h4>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="bi bi-plus-lg me-1"></i> Добавить пользователя
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-wrapper mb-4">
        <div class="table-inner">
            <table class="table">
                <thead class="text-center">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Имя</th>
                        <th scope="col">E-mail</th>
                        <th scope="col">Роль</th>
                        <th scope="col">Дата регистрации</th>
                        <th scope="col">Управление</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse ($users as $user)
                        <tr>
                            <th scope="row">{{ $user->id }}</th>
                            <td>{{ htmlspecialchars($user->name) }}</td>
                            <td>{{ htmlspecialchars($user->email) }}</td>
                            <td>
                                <span class="badge {{ $user->role === 'admin' ? 'badge-admin' : 'badge-user' }}">
                                    {{ $user->role === 'admin' ? 'Администратор' : 'Пользователь' }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" 
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Изменить
                                    </button>
                                  <ul class="dropdown-menu dropdown-menu-custom">
    @if ($user->role === 'admin' && $user->id !== $currentUser->id)
        <li>
            <a class="dropdown-item dropdown-item-custom" href="#" 
               onclick="changeRole({{ $user->id }}, 'user'); return false;">
                <i class="bi bi-arrow-down-circle"></i>Понизить
            </a>
        </li>
    @elseif ($user->role === 'user')
        <li>
            <a class="dropdown-item dropdown-item-custom" href="#" 
               onclick="changeRole({{ $user->id }}, 'admin'); return false;">
                <i class="bi bi-arrow-up-circle"></i>Повысить
            </a>
        </li>
    @endif
    
    @if ($user->id === $currentUser->id)
        <li>
            <a class="dropdown-item dropdown-item-custom" href="#" 
              onclick="editUser({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}')">
                <i class="bi bi-pencil-square"></i>Редактировать
            </a>
        </li>
    @endif
    
    @if ($user->id === $currentUser->id)
        <li>
            <a class="dropdown-item dropdown-item-custom" href="#" 
               onclick="changePassword({{ $user->id }}, '{{ addslashes($user->name) }}')">
                <i class="bi bi-key"></i>Сменить пароль
            </a>
        </li>
    @endif
    
    <li><hr class="dropdown-divider-custom"></li>
    
    @if ($user->id !== $currentUser->id)
        <li>
            <a class="dropdown-item dropdown-item-custom logout-item" href="#" 
               onclick="deleteUser({{ $user->id }}); return false;">
                <i class="bi bi-trash"></i>Удалить
            </a>
        </li>
    @else
        <li>
            <a class="dropdown-item dropdown-item-custom logout-item" href="#" 
               onclick="alert('Нельзя удалить самого себя'); return false;">
                <i class="bi bi-trash"></i>Удалить
            </a>
        </li>
    @endif
</ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Пользователи не найдены</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Модалка добавления пользователя --}}
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title">Добавить пользователя</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addUserForm" action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-danger form-general-error" style="display:none;"></div>
                    
                    <div class="mb-3">
                        <label for="add_name" class="form-label required-field">Имя</label>
                        <input type="text" class="form-control" id="add_name" name="name" required maxlength="255">
                        <div class="validation-message" id="add_name_error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="add_email" class="form-label required-field">E-mail</label>
                        <input type="email" class="form-control" id="add_email" name="email" required maxlength="255">
                        <div class="validation-message" id="add_email_error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="add_password" class="form-label required-field">Пароль</label>
                        <div class="password-input-wrapper">
                            <input type="password" class="form-control form-control-with-icon" 
                                   id="add_password" name="password" required minlength="6">
                            <button type="button" class="password-toggle" onclick="togglePassword('add_password')">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div class="form-text">Минимум 6 символов</div>
                        <div class="validation-message" id="add_password_error"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required-field">Роль</label>
                        <div class="role-selector">
                            <div class="role-option" onclick="selectRole('user')">
                                <input type="radio" name="role" value="user" id="add_role_user" required>
                                <div>
                                    <div class="fw-bold">Пользователь</div>
                                    <small class="text-muted">Обычный доступ к системе</small>
                                </div>
                            </div>
                            <div class="role-option mt-2" onclick="selectRole('admin')">
                                <input type="radio" name="role" value="admin" id="add_role_admin">
                                <div>
                                    <div class="fw-bold">Администратор</div>
                                    <small class="text-muted">Полный доступ к админ-панели</small>
                                </div>
                            </div>
                        </div>
                        <div class="validation-message" id="add_role_error"></div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="submit" class="btn btn-primary px-5 w-100">
                        <i class="bi bi-save me-1"></i> Добавить пользователя
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Модалка редактирования --}}
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title">Редактировать пользователя</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUserForm" action="" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="user_id" id="edit_user_id">
                <div class="modal-body">
                    <div class="alert alert-danger form-general-error" style="display:none;"></div>
                    
                    <div class="mb-3">
                        <label for="edit_name" class="form-label required-field">Имя</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required maxlength="255">
                        <div class="validation-message" id="edit_name_error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label required-field">E-mail</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required maxlength="255">
                        <div class="validation-message" id="edit_email_error"></div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="submit" class="btn btn-primary px-5 w-100">
                        <i class="bi bi-save me-1"></i> Сохранить изменения
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Модалка смены пароля --}}
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title">Сменить пароль</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="changePasswordForm" action="" method="POST">
                @csrf
                <input type="hidden" name="user_id" id="cp_user_id">
                <div class="modal-body">
                    <div class="alert alert-danger form-general-error" style="display:none;"></div>
                    
                    <div class="mb-3">
                        <label class="form-label">Пользователь</label>
                        <input type="text" class="form-control" id="cp_user_name" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="cp_new_password" class="form-label required-field">Новый пароль</label>
                        <div class="password-input-wrapper">
                            <input type="password" class="form-control form-control-with-icon" 
                                   id="cp_new_password" name="new_password" required minlength="6">
                            <button type="button" class="password-toggle" onclick="togglePassword('cp_new_password')">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div class="form-text">Минимум 6 символов</div>
                        <div class="validation-message" id="cp_new_password_error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="cp_confirm_password" class="form-label required-field">Подтвердите пароль</label>
                        <div class="password-input-wrapper">
                          <input type="password" 
       class="form-control form-control-with-icon" 
       id="cp_confirm_password"
       name="new_password_confirmation"
       required minlength="6">
                            <button type="button" class="password-toggle" onclick="togglePassword('cp_confirm_password')">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div class="validation-message" id="cp_confirm_password_error"></div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="submit" class="btn btn-primary px-5 w-100" id="cp_submit_btn" disabled>
                        <i class="bi bi-key me-1"></i> Сменить пароль
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initAjaxForms();
    initPasswordValidation();
});

function initAjaxForms() {
    const addForm = document.getElementById('addUserForm');
    if (addForm) {
        addForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitFormAjax(addForm, '#addUserModal', () => location.reload());
        });
    }
    
    const editForm = document.getElementById('editUserForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitFormAjax(editForm, '#editUserModal', () => location.reload());
        });
    }
    
    const cpForm = document.getElementById('changePasswordForm');
    if (cpForm) {
        cpForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitFormAjax(cpForm, '#changePasswordModal', () => location.reload());
        });
    }
}

function deleteUser(userId) {
    if (!confirm('Вы уверены, что хотите удалить этого пользователя? Это действие нельзя отменить.')) {
        return;
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    
    if (!csrfToken) {
        alert('Ошибка: CSRF-токен не найден. Обновите страницу.');
        return;
    }
    
    fetch(`/admin/users/${userId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(async response => {
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            const data = await response.json();
            
            if (response.ok && data.success) {
                location.reload();
            } else {
                const errorMsg = data.errors?.self?.[0] || 
                                data.errors?.database?.[0] || 
                                data.message || 
                                'Ошибка при удалении';
                alert(errorMsg);
            }
        } else {
            const text = await response.text();
            alert('Сервер вернул некорректный ответ. Статус: ' + response.status);
        }
    })
    .catch(error => {
        alert('Ошибка соединения. Проверьте консоль (F12) для деталей.');
    });
}

function submitFormAjax(form, modalSelector, onSuccess) {
    const modal = bootstrap.Modal.getInstance(document.querySelector(modalSelector));
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Сохранение...';
    clearFormErrors(form);
    
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: form.method.toUpperCase(),
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || 
                           document.querySelector('[name="csrf-token"]')?.content
        }
    })
    .then(async response => {
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            const data = await response.json();
            if (response.status === 422) {
                throw { type: 'validation', errors: data.errors };
            }
            if (response.status >= 400) {
                throw { type: 'server', message: data.errors?.database?.[0] || data.errors?.self?.[0] || data.message || 'Ошибка' };
            }
            return data;
        }
        window.location.reload();
    })
    .then(data => {
        if (data.success) {
            modal?.hide();
            onSuccess?.();
        }
    })
    .catch(error => {
        if (error.type === 'validation') {
            displayValidationErrors(form, error.errors);
        } else {
            showGeneralError(form, error.message || 'Произошла ошибка');
        }
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
    });
}

function editUser(id, name, email) {
    document.getElementById('edit_user_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_email').value = email;

    const form = document.getElementById('editUserForm');
    form.action = `/admin/users/${id}`;

    clearFormErrors(form);

    const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
    modal.show();
}

function clearFormErrors(form) {
    form.querySelectorAll('.is-invalid, .is-valid').forEach(el => {
        el.classList.remove('is-invalid', 'is-valid');
    });
    form.querySelectorAll('.validation-message').forEach(el => {
        el.style.display = 'none';
        el.textContent = '';
    });
    const generalError = form.querySelector('.form-general-error');
    if (generalError) {
        generalError.style.display = 'none';
        generalError.textContent = '';
    }
}

function displayValidationErrors(form, errors) {
    for (const [field, messages] of Object.entries(errors)) {
        const input = form.querySelector(`[name="${field}"]`);
        if (input) {
            input.classList.add('is-invalid');
            const errorEl = document.getElementById(`${form.id.replace('Form', '')}_${field}_error`);
            if (errorEl) {
                errorEl.textContent = messages[0];
                errorEl.style.display = 'block';
            }
        }
    }
    const firstError = form.querySelector('.is-invalid');
    if (firstError) {
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        firstError.focus?.();
    }
}

function showGeneralError(form, message) {
    const errorBlock = form.querySelector('.form-general-error');
    if (errorBlock) {
        errorBlock.textContent = message;
        errorBlock.style.display = 'block';
    }
}

function changeRole(userId, newRole) {
    const roleText = newRole === 'admin' ? 'Администратор' : 'Пользователь';
    const actionText = newRole === 'admin' ? 'Повысить' : 'Понизить';
    
    if (!confirm(`${actionText} пользователя до роли "${roleText}"?`)) return;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    
    if (!csrfToken) {
        alert('Ошибка: CSRF-токен не найден. Обновите страницу.');
        return;
    }
    
    fetch(`/admin/users/${userId}/role`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ 
            role: newRole,
            _method: 'PUT'
        })
    })
    .then(response => {
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json().then(data => ({ response, data }));
        } else {
            return response.text().then(text => {
                throw new Error('Сервер вернул не JSON. Статус: ' + response.status);
            });
        }
    })
    .then(({ response, data }) => {
        if (response.ok && data.success) {
            updateRoleInTable(userId, newRole);
            showTemporarySuccess('Роль успешно изменена');
        } else {
            const errorMsg = data.errors?.self?.[0] || 
                            data.errors?.database?.[0] || 
                            data.message || 
                            'Ошибка при смене роли';
            alert(errorMsg);
        }
    })
    .catch(error => {
        if (error.message.includes('JSON')) {
            alert('Сервер вернул некорректный ответ. Проверьте логи Laravel.');
        } else if (error.message.includes('405')) {
            alert('Метод не разрешён. Проверьте маршруты.');
        } else if (error.message.includes('419')) {
            alert('CSRF-токен недействителен. Обновите страницу.');
        } else if (error.message.includes('404')) {
            alert('Маршрут не найден. Проверьте routes/web.php');
        } else if (error.message.includes('500')) {
            alert('Внутренняя ошибка сервера. Проверьте storage/logs/laravel.log');
        } else {
            alert('Ошибка соединения: ' + error.message);
        }
    });
}

function updateRoleInTable(userId, newRole) {
    const rows = document.querySelectorAll('tbody tr');
    let targetRow = null;
    
    rows.forEach(row => {
        const cells = row.querySelectorAll('td, th');
        cells.forEach(cell => {
            if (cell.textContent.includes(userId) && cell.cellIndex === 0) {
                targetRow = row;
            }
        });
    });
    
    if (!targetRow) return;
    
    const badgeCell = targetRow.cells[3];
    if (badgeCell) {
        const badgeClass = newRole === 'admin' ? 'badge-admin' : 'badge-user';
        const badgeText = newRole === 'admin' ? 'Администратор' : 'Пользователь';
        badgeCell.innerHTML = `<span class="badge ${badgeClass}">${badgeText}</span>`;
    }
    
    const actionsCell = targetRow.cells[targetRow.cells.length - 1];
    if (actionsCell) {
        const roleLink = newRole === 'admin' 
            ? `<li><a class="dropdown-item dropdown-item-custom" href="#" onclick="changeRole(${userId}, 'user'); return false;"><i class="bi bi-arrow-down-circle"></i>Понизить</a></li>`
            : `<li><a class="dropdown-item dropdown-item-custom" href="#" onclick="changeRole(${userId}, 'admin'); return false;"><i class="bi bi-arrow-up-circle"></i>Повысить</a></li>`;
        
        const otherLinks = actionsCell.innerHTML
            .replace(/<li>[\s\S]*?<i class="bi bi-arrow-(up|down)-circle"[^>]*>[^<]*<\/a><\/li>/, '')
            .trim();
        
        actionsCell.innerHTML = `
            <div class="btn-group">
                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Изменить</button>
                <ul class="dropdown-menu dropdown-menu-custom">
                    ${roleLink}
                    ${otherLinks}
                </ul>
            </div>
        `;
        
        actionsCell.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(btn => {
            bootstrap.Dropdown.getOrCreateInstance(btn);
        });
    }
}

function showTemporarySuccess(message) {
    const alert = document.createElement('div');
    alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
    alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alert);
    
    setTimeout(() => {
        alert.classList.remove('show');
        setTimeout(() => alert.remove(), 150);
    }, 3000);
}

function changePassword(id, name) {
    document.getElementById('cp_user_id').value = id;
    document.getElementById('cp_user_name').value = name;
    document.getElementById('cp_new_password').value = '';
    document.getElementById('cp_confirm_password').value = '';
    document.getElementById('cp_submit_btn').disabled = true;
    document.getElementById('changePasswordForm').action = `/admin/users/${id}/password`;
    clearFormErrors(document.getElementById('changePasswordForm'));
    new bootstrap.Modal(document.getElementById('changePasswordModal')).show();
}

function initPasswordValidation() {
    const newPassword = document.getElementById('cp_new_password');
    const confirmPassword = document.getElementById('cp_confirm_password');
    const submitBtn = document.getElementById('cp_submit_btn');
    
    function validate() {
        if (newPassword && confirmPassword && submitBtn) {
            submitBtn.disabled = !(newPassword.value === confirmPassword.value && newPassword.value.length >= 6);
        }
    }
    
    newPassword?.addEventListener('input', validate);
    confirmPassword?.addEventListener('input', validate);
}

function selectRole(role) {
    document.getElementById('add_role_user').checked = role === 'user';
    document.getElementById('add_role_admin').checked = role === 'admin';
}

function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = input?.nextElementSibling?.querySelector('i');
    if (!input || !icon) return;
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}
</script>
@endpush