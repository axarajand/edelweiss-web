{{-- 
    Avatar component — menampilkan foto profil atau initial generated.
    Pakai: <x-avatar :user="$user" class="w-10 h-10" />
           <x-avatar :user="auth()->user()" />
--}}
@props(['user', 'class' => 'w-10 h-10'])

<img src="{{ $user->avatar_url }}"
     alt="{{ $user->name }}"
     class="{{ $class }} rounded-full object-cover bg-slate-200 dark:bg-slate-700 ring-2 ring-white dark:ring-slate-900"
     loading="lazy">
