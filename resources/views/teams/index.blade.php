<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('All Clinics') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-hidden overflow-x-auto bg-white">
                        <div class="flex min-w-full flex-col align-middle sm:gap-4">
                            <div class="flex items-center">
                                @can(\App\Enums\PermissionEnum::CreateTeam)
                                    <a href="{{ route('teams.create') }}" class="underline">Add new clinic</a>
                                @endcan
                            </div>
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
                                        <th class="bg-gray-50 px-6 py-3 text-left"></th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-solid divide-gray-200 bg-white">
                                    @foreach ($teams as $team)
                                        <tr class="bg-white">
                                            <td class="whitespace-no-wrap px-6 py-4 text-sm leading-5 text-gray-900">
                                                {{ $team->name }}
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
