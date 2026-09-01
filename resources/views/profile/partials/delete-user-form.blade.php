<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Удалить аккаунт') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('После удаления вашей учетной записи все связанные с ней ресурсы и данные будут безвозвратно удалены. Перед удалением, пожалуйста, сохраните все данные, которые вы хотели бы оставить у себя.') }}
        </p>
    </header>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')
        
            <button type="submit" class="btn btn-danger" 
                onclick="return confirm('Вы уверены, что хотите удалить аккаунт?')"
                >Удалить
            </button>
        </form>
</section>
