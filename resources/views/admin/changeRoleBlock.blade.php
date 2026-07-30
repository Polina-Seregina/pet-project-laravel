<div style="margin: 5px 350px 5px 350px; background-color: #ffefd5" class="widget-contact">
    <form method="post" action="{{ route('admin.update', ['user' => $user]) }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <x-input-label for="role" style="font-size: 20px;" value="Изменить роль"/>

        <select name="role" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" >
            <option value="user"
            @if ($user->getRoleNames()->first() === 'user')
                selected
            @endif
            > user </option>
            <option value="admin" 
            @if ($user->getRoleNames()->first() === 'admin')
                selected
            @endif> admin </option>
        </select>

        <div style="display: flex; justify-content: center; align-items: center;"  class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
        </div>
    </form>

</div>