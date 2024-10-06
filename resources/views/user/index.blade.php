<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Clinic Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-hidden overflow-x-auto bg-white">
                        <div class="min-w-full align-middle">
                            @can(\App\Enums\PermissionEnum::CreateUser)
                                <a href="{{ route('users.create') }}" class="underline">Create new user</a>
                                <br />
                                <br />
                            @endcan

                            <table class="min-w-full divide-y divide-gray-200 border">
                                <thead>
                                    <tr>
                                        <th class="bg-gray-50 px-6 py-3 text-left">
                                            <span
                                                class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500"
                                            >
                                                Name
                                            </span>
                                        </th>
                                        <th class="bg-gray-50 px-6 py-3 text-left">
                                            <span
                                                class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500"
                                            >
                                                Role
                                            </span>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-solid divide-gray-200 bg-white">
                                    @foreach ($users as $user)
                                        <tr class="bg-white">
                                            <td class="whitespace-no-wrap px-6 py-4 text-sm leading-5 text-gray-900">
                                                {{ $user->name }}
                                            </td>
                                            <td class="whitespace-no-wrap px-6 py-4 text-sm leading-5 text-gray-900">
                                                @foreach ($user->roles as $role)
                                                    {{ $role->name }}
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
