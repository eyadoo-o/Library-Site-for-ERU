<div id="auth-modal" class="fixed inset-0 z-50 hidden overflow-auto bg-black bg-opacity-50 backdrop-filter backdrop-blur-sm flex items-center justify-center">
  <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-md p-6 mx-2">
    <div class="flex justify-between items-center mb-4">
      <h2 id="auth-modal-title" class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ __('messages.login') }}</h2>
      <button id="auth-modal-close" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">&times;</button>
    </div>

    <div id="auth-modal-messages" class="mb-4"></div>

    <div id="login-form-container">
      <form id="login-form" method="POST" action="{{ url('/ClientLogin') }}">
        @csrf
        <div class="space-y-4">
          <input name="email" type="email" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#263e62]" placeholder="{{ __('messages.email_placeholder') }}">
          <input name="password" type="password" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#263e62]" placeholder="{{ __('messages.password_placeholder') }}">
          <div class="flex items-center">
            <input id="remember" type="checkbox" name="remember" class="mr-2"><label for="remember" class="text-sm">{{ __('messages.remember_me') }}</label>
          </div>
          <button type="submit" class="w-full px-4 py-2 bg-[#263e62] text-white rounded-lg hover:bg-[#1d324f]">{{ __('messages.login') }}</button>
        </div>
      </form>
      <p class="mt-4 text-sm text-center">
        {{ __("messages.dont_have_account") }}
        <button id="show-register" class="text-[#bb0017] hover:underline">{{ __('messages.sign_up_link') }}</button>
      </p>
    </div>

    <div id="register-form-container" class="hidden">
      <form id="register-form" method="POST" action="{{ route('client.register') }}">
        @csrf
        <div class="space-y-4">
          <input name="name" type="text" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#263e62]" placeholder="{{ __('messages.name_placeholder') }}">
          <input name="email" type="email" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#263e62]" placeholder="{{ __('messages.email_placeholder') }}">
          <input name="faculty_id" type="text" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#263e62]" placeholder="{{ __('messages.faculty_id_placeholder') }}">
          <input name="password" type="password" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#263e62]" placeholder="{{ __('messages.password_placeholder') }}">
          <input name="password_confirmation" type="password" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#263e62]" placeholder="{{ __('messages.confirm_password_placeholder') }}">
          <button type="submit" class="w-full px-4 py-2 bg-[#bb0017] text-white rounded-lg hover:bg-[#a00014]">{{ __('messages.sign_up_button') }}</button>
        </div>
      </form>
      <p class="mt-4 text-sm text-center">
        {{ __("messages.already_have_account") }}
        <button id="show-login" class="text-[#263e62] hover:underline">{{ __('messages.login_link') }}</button>
      </p>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('auth-modal');
  const btns = document.querySelectorAll('.open-auth-modal');
  const closeBtn = document.getElementById('auth-modal-close');
  const loginFormContainer = document.getElementById('login-form-container');
  const registerFormContainer = document.getElementById('register-form-container');
  const loginForm = document.getElementById('login-form');
  const registerForm = document.getElementById('register-form');
  const title = document.getElementById('auth-modal-title');
  const messagesDiv = document.getElementById('auth-modal-messages');

  function clearMessages() {
    messagesDiv.innerHTML = '';
    messagesDiv.className = 'mb-4';
  }

  function displayMessage(message, type = 'status') {
    clearMessages();
    const messageClass = type === 'error' ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400';
    messagesDiv.innerHTML = `<div class="font-medium text-sm ${messageClass}">${message}</div>`;
  }

  function displayValidationErrors(errors) {
    clearMessages();
    let errorHtml = '<ul class="list-disc list-inside text-sm text-red-600 dark:text-red-400">';
    for (const field in errors) {
      errors[field].forEach(error => {
        errorHtml += `<li>${error}</li>`;
      });
    }
    errorHtml += '</ul>';
    messagesDiv.innerHTML = errorHtml;
  }

  async function handleFormSubmit(event, form) {
    event.preventDefault();
    clearMessages();
    const formData = new FormData(form);
    const action = form.action;
    const method = form.method;

    try {
      const response = await fetch(action, {
        method: method,
        body: formData,
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || form.querySelector('input[name="_token"]').value,
          'Accept': 'application/json',
        }
      });

      const data = await response.json();

      if (!response.ok) {
        if (data.errors) {
          displayValidationErrors(data.errors);
        } else if (data.message) {
          displayMessage(data.message, 'error');
        } else {
          displayMessage("{{ __('messages.unexpected_error') }}", 'error');
        }
        return;
      }

      if (data.status) {
        displayMessage(data.status, 'status');
        if (form.id === 'login-form' && data.redirect_url) {
          setTimeout(() => {
            window.location.href = data.redirect_url;
          }, 1500);
        } else if (form.id === 'register-form') {
           form.reset();
        }
      }

    } catch (error) {
      console.error('Form submission error:', error);
      displayMessage("{{ __('messages.network_error') }}", 'error');
    }
  }

  loginForm.addEventListener('submit', (event) => handleFormSubmit(event, loginForm));
  registerForm.addEventListener('submit', (event) => handleFormSubmit(event, registerForm));

  function openModal(type = 'login') {
    clearMessages();
    title.textContent = type === 'register' ? "{{ __('messages.sign_up_title') }}" : "{{ __('messages.login_title') }}";
    if (type === 'register') {
      loginFormContainer.classList.add('hidden');
      registerFormContainer.classList.remove('hidden');
    } else {
      registerFormContainer.classList.add('hidden');
      loginFormContainer.classList.remove('hidden');
    }
    modal.classList.remove('hidden');
  }

  btns.forEach(btn => btn.addEventListener('click', () => {
    openModal(btn.dataset.modalType);
  }));

  closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
  modal.addEventListener('click', e => { if (e.target === modal) modal.classList.add('hidden'); });

  document.getElementById('show-register').addEventListener('click', () => {
    clearMessages();
    title.textContent = "{{ __('messages.sign_up_title') }}";
    loginFormContainer.classList.add('hidden');
    registerFormContainer.classList.remove('hidden');
  });

  document.getElementById('show-login').addEventListener('click', () => {
    clearMessages();
    title.textContent = "{{ __('messages.login_title') }}";
    registerFormContainer.classList.add('hidden');
    loginFormContainer.classList.remove('hidden');
  });

  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.has('auth_modal_error')) {
    displayMessage(urlParams.get('auth_modal_error'), 'error');
    openModal(urlParams.get('form_type') || 'login');
  } else if (urlParams.has('auth_modal_status')) {
    displayMessage(urlParams.get('auth_modal_status'), 'status');
    openModal(urlParams.get('form_type') || 'login');
  }

});
</script>
