const tabLogin = document.getElementById('tab-login');
const tabRegister = document.getElementById('tab-register');
const formLogin = document.getElementById('form-login');
const formRegister = document.getElementById('form-register');

tabLogin.addEventListener('click', () => {
    formLogin.classList.remove('hidden');
    formRegister.classList.add('hidden');
    tabLogin.classList.add('text-teal-700', 'border-b-2', 'border-teal-700');
    tabRegister.classList.remove('text-teal-700', 'border-b-2', 'border-teal-700');
    tabRegister.classList.add('text-slate-500');
});

tabRegister.addEventListener('click', () => {
    formLogin.classList.add('hidden');
    formRegister.classList.remove('hidden');
    tabRegister.classList.add('text-teal-700', 'border-b-2', 'border-teal-700');
    tabLogin.classList.remove('text-teal-700', 'border-b-2', 'border-teal-700');
    tabLogin.classList.add('text-slate-500');
});